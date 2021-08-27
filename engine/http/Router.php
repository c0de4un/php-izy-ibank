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

namespace Izy\Http;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// INCLUDES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( 'IRequest.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// USE
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

final class Router
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var Router */
    private static $instance = null;

    /** @var IRequest */
    private $request = null;

    /** @var array[Route] */
    private $routes = [];

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Resolves Request route
     *
     * @param IRequest $request
     *
     * @return void
     */
    public static function HandleRequest( IRequest $request ): void
    {
        if ( !self::$instance ) {
            self::$instance = new Router();
        }

        // Cache Request instance
        self::$instance->request = $request;

        // Split Request URI
        self::$instance->uri_sections = explode('/', $request->getUrl()->getUri());

        // Recursively handle Request URI parts
        self::$instance->onHandleRequest( self::$instance->uri_sections[0], 0 );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PRIVATE
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    private function __construct()
    {
        $this->routes = require_once( APP_DIR . '/configs/routes.php' );
    }

    /**
     * Recursive route handler
     *
     * @param string $name
     * @param int    $depth
     *
     * @return void
    */
    private function onHandleRequest( string $name, int $depth ): void
    {
        if ( $depth > MAX_RECURSION_DEPTH ) {
            throw new \Exception( 'Recursion limit exceeded', 500 );
        }

        // No handler
        $route_config = $this->routes[$name] ?? null;
        if ( !$route_config && !$depth ) {
            throw new \Exception( "Unknown URI: '{$name}'", 404 );
        }

        // Controller for Route-level
        $controller_class = $route_config['controller'] ?? null;
        if ( $controller_class ) {
            $function_name       = $route_config['function'];
            $controller_instance = new $controller_class();
            $controller_instance->$function_name($this->request);

            return;
        }

        $next_depth      = $depth++;
        $next_route_name = $this->uri_sections[$next_depth];

        $this->onHandleRequest( $next_route_name, $next_depth );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
