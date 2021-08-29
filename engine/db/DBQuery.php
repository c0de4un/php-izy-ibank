<?php

/**
 * All rights reserved.
 * License: see LICENSE.txt
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS ``AS
 * IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
**/

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// NAMESPACE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

namespace Izy\DB;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// INCLUDS
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( 'IDBQuery.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// USE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

use PDO;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * DBQuery - base db query
 *
 * @version 1.0
*/
abstract class DBQuery implements IDBQuery
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var PDO */
    protected $connection = null;

    /** @var string */
    protected $compiled_query = '';

    /** @var string */
    protected $selects = '';

    /** @var string */
    protected $wheres = '';

    /** @var bool */
    protected $new_where = true;

    /** @var string */
    protected $from = '';

    /** @var string */
    protected $order_by = '';

    /** @var string */
    protected $compiled = '';

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Execute raw-sql query
     *
     * @param string $sql_query
     *
     * @return array[object] - array of stdClass-based objects with each field as column from DB
    */
    public function Raw( string $sql_query ): array
    { return $this->execute( $sql_query ); }

    /**
     * Add SELECT statement
     *
     * @param strin $select
     *
     * @return IDBQuery
    */
    public function select( string $select ): IDBQuery
    {
        $this->selects .= $this->selects ? ", {$select}" : "SELECT {$select}";

        return $this;
    }

    /**
     * Adds FROM statement
     *
     * @param string $table
     *
     * @return IDBQuery
     */
    public function from( string $table ): IDBQuery
    {
        $this->from = "FROM `$table`";

        return $this;
    }

    /**
     * Add WHERE or AND statement
     *
     * @param string           $column
     * @param string||in||bool $value
     *
     * @return IDBQuery
    */
    public function where( string $column, $value ): IDBQuery
    {
        if ( $this->new_where ) {
            $this->new_where = false;

            $this->wheres .= "WHERE {$column} '{$value}'";
        } else {
            $this->wheres .= " AND {$column} '{$value}'";
        }

        return $this;
    }

    /**
     * Add OR statement
     *
     * @param string           $column
     * @param string||in||bool $value
     *
     * @return IDBQuery
    */
    public function orWhere( string $column, $value ): IDBQuery
    {
        $this->wheres .= " OR {$column} '{$value}'";

        return $this;
    }

    /**
     * Increase WHERE-statement depth
     * Add '(' to query
     *
     * @return IDBQuery
    */
    public function groupStart(): IDBQuery
    {
        $this->wheres .= ' ( ';

        $this->new_where = true;

        return $this;
    }

    /**
     * Decrease WHERE-statement depth
     * Add ')' to query
     *
     * @return IDBQuery
    */
    public function groupEnd(): IDBQuery
    {
        $this->wheres .= ' ) ';

        $this->new_where = false;

        return $this;
    }

    /**
     * Adds ORDER BY statement
     *
     * @param string $columns
     * @param string $direction @example: ASC
     *
     * @return IDBQuery
    */
    public function orderBy( string $column, string $direction ): IDBQuery
    {
        $this->order_by = "ORDER BY `{$column}` {$direction}";

        return $this;
    }

    /**
     * Compile to SQL-query for PDO & Execute
     *
     * @return array[]
    */
    public function Commit(): array
    {
        $this->compile();

        return $this->execute( $this->compiled );
    }

    /**
     * Returns compiled SQL-query string
     *
     * @return string
    */
    public function getCompiled(): string
    {
        if ( !$this->compiled ) {
            $this->compile();
        }

        return $this->compiled;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PROTECTED
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * @param PDO $pdo
    */
    protected function __construct( PDO $pdo )
    {
        $this->connection = $pdo;
    }

    /**
     * Compiles parts of query to ready SQL-query string
     *
     * @return void
    */
    protected function compile()
    {
        $this->compiled = "
            {$this->selects}
            {$this->from}
        ";

        if ( $this->wheres ) { $this->compiled .= "
             {$this->wheres}"; }

        if ( $this->order_by ) { $this->compiled .= "
             {$this->order_by}"; }
    }

    /**
     * Executes SQL-query string with PDO
     *
     * @param string $sql_query
     *
     * @reutrn array
    */
    final protected function execute( $sql_query )
    {
        $output = [];

        /** @var PDOStatement */
        $pdo_statement = $this->connection->prepare( $sql_query );

        // Execute & fetch PDOStatement
        if ( !$pdo_statement->execute() ) {
            throw new \Exception( $pdo_statement->errorInfo()[2] ?? 'Unknown SQL-error', 500 );
        }

        // Iterate Rows
        while ( $row = $pdo_statement->fetch(PDO::FETCH_LAZY) ) {
            $output []= $row;
        }

        return $output;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
