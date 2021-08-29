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

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * IDBQuery - database connection-based query behavior contract
 *
 * @version 1.0
*/
interface IDBQuery
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Execute raw-sql query
     *
     * @param string $sql_query
     *
     * @return array[object] - array objects with each field as column from DB
    */
    public function Raw( string $sql_query ): array;

    /**
     * Add SELECT statement
     *
     * @param strin $select
     *
     * @return IDBQuery
    */
    public function select( string $select ): IDBQuery;

    /**
     * Adds FROM statement
     *
     * @param string $table
     *
     * @return IDBQuery
     */
    public function from( string $table ): IDBQuery;

    /**
     * Add WHERE or AND statement
     *
     * @param string           $column
     * @param string||in||bool $value
     *
     * @return IDBQuery
    */
    public function where( string $column, $value ): IDBQuery;

    /**
     * Add OR statement
     *
     * @param string           $column
     * @param string||in||bool $value
     *
     * @return IDBQuery
    */
    public function orWhere( string $column, $value ): IDBQuery;

    /**
     * Increase WHERE-statement depth
     * Add '(' to query
     *
     * @return IDBQuery
    */
    public function groupStart(): IDBQuery;

    /**
     * Decrease WHERE-statement depth
     * Add ')' to query
     *
     * @return IDBQuery
    */
    public function groupEnd(): IDBQuery;

    /**
     * Adds ORDER BY statement
     *
     * @param string $columns
     * @param string $direction @example: ASC
     *
     * @return IDBQuery
    */
    public function orderBy( string $column, string $direction ): IDBQuery;

    /**
     * Compile to SQL-query for PDO & Execute
     *
     * @return array[]
    */
    public function Commit(): array;

    /**
     * Returns compiled SQL-query string
     *
     * @return string
    */
    public function getCompiled(): string;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
