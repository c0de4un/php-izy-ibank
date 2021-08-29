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
require_once( 'MySQLQuery.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// USE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

use PDO;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * DBFactory - manage Database connections & queries
 *
 * @version 1.0
*/
final class DBFactory
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var DBFactory */
    private static $instance = null;

    /** @var array */
    private $config = [];

    /** @var array[string] */
    private $connections_names = [];

    /** @var array[string=>IDBConnection] */
    private $connections = [];

    /** @var array[string=>IDBQuery] */
    private $queries = [];

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Initialize DBFactory instance, if required
     *
     * @return DBFactory
    */
    public static function Instance()
    {
        if ( !self::$instance ) {
            self::$instance = new DBFactory();
        }

        return self::$instance;
    }

    /**
     * Establish PDO connection
     *
     * @param string $db_name
     * @param array  $connection_data
     *
     * @return PDO
    */
    public function establishPDOConnection( string $db_name, $connection_data ): PDO
    {
        $dsn = "{$connection_data['type']}:host={$connection_data['host']};dbname={$connection_data['db']};charset={$connection_data['charset']};";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->conenctions[$db_name] = new PDO( $dsn, $connection_data['login'], $connection_data['password'], $options );

        return $this->conenctions[$db_name];
    }

    /**
     * Returns database query
     *
     * @param string [$db_name = null] - database connection name.
     *
     * @return IDBQuery
    */
    public function getQuery( string $db_name = null ): IDBQuery
    {

        // Use default db
        if ( !$db_name ) {
            $db_name = $this->connections_names[0];
        }

        // Establish PDO-Connection
        if ( !isset($this->connections[$db_name]) ) {

            // Connection config
            $connection_data = &$this->config['connections'][$db_name];

            /** Establish PDO Connection @var PDO */
            $pdo = $this->establishPDOConnection( $db_name, $connection_data );

            // Allocate Query
            $this->queries[$db_name] = $this->allocQuery( $pdo, $connection_data['type'] );
        }

        // Get Query
        $db_query = $this->queries[$db_name];

        return $db_query;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PRIVATE
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    private function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Load DBFactory config
     *
     * @return void
    */
    private function loadConfig(): void
    {
        $this->config = include( APP_DIR . '/configs/db.php' );

        $this->connections_names = array_keys( $this->config['connections'] );
    }

    /**
     * Create new DBQuery
     *
     * @param PDO    $pdo
     * @param string $db_type
     * @example: 'mysql'
     *
     * @return IDBQuery
    */
    private function allocQuery( $pdo, $db_type ): IDBQuery
    {
        $query_adapters = [
            'mysql' => MySQLQuery::class,
        ];

        return new $query_adapters[$db_type]( $pdo );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
