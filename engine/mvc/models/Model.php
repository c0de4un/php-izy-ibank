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

namespace Izy\MVC\Models;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// INCLUDS
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( 'IModel.php' );
require_once( IZY_DIR . '/mvc/Entity.php' );

require_once( IZY_DIR . '/db/DBFactory.php' );
require_once( IZY_DIR . '/db/IDBQuery.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// USE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

use Izy\MVC\Entity;
use Izy\DB\DBFactory;
use Izy\DB\IDBQuery;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * Model - base model class
 *
 * @version 1.0
*/
abstract class Model extends Entity implements IModel
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // IModel.OVERRIDE.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Deserialize instance
     *
     * @param int||string [$primary_key = null] - null to use already set primary-key
     *
     * @return bool - 'true' on success, 'false' if row by primary-key were not found
    */
    public function Load( $primary_key = null ): bool
    {
        // Set Primary-Key value
        if ( $primary_key ) {
            $primary_field = get_class($this)::PRIMARY_FIELD;
            $this->$primary_field = $primary_key;
        }

        // @TODO: Model::load()
        return false;
    }

    /**
     * Serialize instnace
     *
     * @return array
     * @example
     * [
     *     'success' => true,
     *     'errors'  => [
     *         'id'         => 'Can\'t rewrite this column because it is unique per row',
     *         'created_at' => 'This column can\'t be null',
     *     ],
     * ]
    */
    public function Save(): array
    {
        // @TODO: Model::save()
        return [];
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Returns all rows
     *
     * @return array[Model]
    */
    public function getAll(): array
    {// @TODO: Model::getAll()
        // Get DBQuery
        $rows = $this->db()->Raw( "SELECT * FROM users" );

        var_dump( $rows );
        exit( 'Model::getAll: PDO-Test' );

        return $rows;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PROTECTED
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * @param int||string [$primary = null] - is set, used to load instance
     * with primary-key
    */
    protected function __construct( $primary_key = null )
    {
        parent::__construct();

        if ( $primary_key ) {
            if ( !$this->Load( $primary_key ) ) {
                throw new \Exception( "Record #'{$primary_key}' is not found", 404 );
            }
        }
    }

    /**
     * Returns database connection interface
     *
     * @param string [$db_name = null]
     *
     * @return IDBConnection
    */
    protected function db( string $db_name = null ): IDBQuery
    { return DBFactory::Instance()->getQuery( $db_name ); }

    /**
     * Search row by primary key
     *
     * @param array[string=>int||string||bool] $wheres
     *
     * @return Model
    */
    protected function getByFields( array $wheres ): ?Model
    {
        // @TODO: Model::getByFields()

        return null;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
