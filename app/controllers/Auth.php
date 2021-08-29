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

namespace c0de4un\IBank\Controllers;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// INCLUDES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( IZY_DIR . '/mvc/controllers/Controller.php' );

require_once( APP_DIR . '/models/User.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// USE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

use c0de4un\IBank\Models\User;

use Izy\MVC\Controllers\Controller;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * Auth controller
 *
 * @version 1.0
*/
final class Auth extends Controller
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    public function __construct()
    {
        parent::__construct();

        $this->User_model = $this->loader()->model( 'User_model' );
    }

    /**
     * Render index
     *
     * @method GET
     *
     * @return void
    */
    public function index(): void
    {
        /** Get User @var User */
        $users = $this->User_model->getAll();
        var_dump( $users );
        exit( 'Auth::index: Model::getAll() Test' );

        // Render Auth
        $this->renderer()
            ->Render( 'auth', ['title' => 'Hello World !'] );
        // exit('
        //     <html>
        //         <head>
        //             <meta charset="UTF-8">
        //             <title>Привет мир !</title>
        //         </head>
        //         <body>
        //             <h2>Привет мир !</h2>
        //         </body>
        //     </html>
        // ');

        // $this->response()
            // ->status( true )
            // ->JSON( 'title', 'hello world !' )
            // ->Commit();
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
