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
// IMPORT
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

require_once( 'IResponse.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * Response (factory & singleton)
 *
 * @version 1.0
*/
final class Response implements IResponse
{// @TODO: Response

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var Response */
    private static $instance = null;

    /** @var array */
    private $default_headers = null;

    /** @var int */
    private $code = 200;

    /** @var array */
    private $headers = [];

    /** @var bool */
    private $status = false;

    /** @var array */
    private $data = [];

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Initialize, if required, and return Response instance
     *
     * @return IResponse
    */
    public static function Initialize(): IResponse
    {
        if ( !self::$instance ) {
            self::$instance = new Response();
        }

        return self::$instance;
    }

    /**
     * Set status
     *
     * Status is a additional field for ajax-based responses.
     * Goes with data (json-body)
     *
     * @param bool $status - 'true' for success, 'false' for failure
     *
     * @return IResponse
    */
    public function Status( bool $status ): IResponse
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set header value
     *
     * @param string $key
     * @param string $value
     *
     * @return IResponse
    */
    public function Header( $key, $value ): IResponse
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Add data to response
     *
     * (?) If key os null, merge of matching items will be overwritten wit new values
     *
     * @param string $key. null to merge given array or object
     * with already added data
     *
     * @param string||int $key
     * @param any         $value
     *
     * @return IResponse
    */
    public function JSON( $key, $value ): IResponse
    {
        // Set Content-Type headers to JSON
        $content_type = $this->headers['Content-Type'] ?? null;
        if ( !$content_type || $content_type !== 'application/json; charset=UTF-8' ) {
            $this->Header( 'Content-Type', 'application/json; charset=UTF-8' );
        }

        // Append/Set data
        if ( $key ) {
            $this->data[$key] = $value;
        } elseif ( is_array($value) ) {
            $this->data = array_merge( $this->data, $value );
        } else {
            foreach ( $value as $key => $new_value ) {
                $this->data[$key] = $new_value;
            }
        }

        return $this;
    }

    /**
     * Reset Response state.
     *
     * @return IResponse
    */
    public function Reset(): IResponse
    {
        // Reset status
        $this->status = false;

        // Reset data
        $this->data = [];

        // Remove custom headers
        header_remove();

        // Restore default values
        foreach ( $this->default_headers as $value ) {
            header($value);
        }

        return $this;
    }

    /**
     * Commit response
     *
     * @return void
    */
    public function Commit(): void
    {
        // Headers
        foreach( $this->headers as $key => $value ) {
            // echo PHP_EOL . "{$key}: {$value}" . PHP_EOL;
            header( "{$key}: {$value}" );
        }

        http_response_code($this->code);

        exit(
            json_encode([
                'status' => $this->status,
                'data'   => $this->data,
            ])
        );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PRIVATE
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    private function __construct()
    {
        $this->default_headers = headers_list();

        $this->code = http_response_code();
        if ( !is_integer($this->code) ) {
            $this->code = 200;
        }
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
