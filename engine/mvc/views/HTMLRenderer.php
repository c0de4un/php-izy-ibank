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

namespace Izy\MVC\Views;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// INCLUDES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( IZY_DIR . '/mvc/views/IRenderer.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * HTMLRenderer - render HTML-based content
 *
 * @version 1.0
*/
final class HTMLRenderer implements IRenderer
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var HTMLRenderer */
    private static $instance = null;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Initialize HTMLRenderer instance
     *
     * @return IRenderer
    */
    public static function Instance(): IRenderer
    {
        if ( !self::$instance ) {
            self::$instance = new HTMLRenderer();
        }

        return self::$instance;
    }

    /**
     * Render HTML-content to string
     *
     * @param string $path
     * @param array  [ $view_data = [] ]
     *
     * @return string
    */
    public function RenderToString( string $path, array $view_data = [] ): string
    { return $this->onRender( $path, $view_data ); }

    /**
     * Render HTML-content to an output buffer
     *
     * @param string $path
     * @param array  [ $view_data = [] ]
     *
     * @return void
    */
    public function Render( string $path, array $view_data = [] ): void
    { echo $this->onRender( $path, $view_data ); }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PRIVATE
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    private function __construct()
    {
    }

    /**
     * Render HTML-content to string
     *
     * @param string $path
     * @param array  $view_data
     *
     * @return string
    */
    private function onRender( string $path, $view_data ): string
    {
        // Stash data into symbols table
        extract( $view_data, EXTR_SKIP | EXTR_REFS );

        // Start Buffering
        ob_start( null, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE );

        // Include View
        include( APP_DIR . "/views/{$path}.php" );

        // Get Buffer-Content & Release it
        return ob_get_clean( );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
