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

require_once( 'IUrl.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * Url
 *
 * @version 1.0
*/
final class Url implements IUrl
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var string (@example: http/https) */
    private $protocol = null;

    /** @var bool */
    private $ssl = null;

    /** @var string */
    private $origin = null;

    /** @var int */
    private $port = null;

    /** @var string */
    private $host = null;

    /** @var string */
    private $uri = null;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // OVERRIDE.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Returns ssl-flag
     *
     * @return bool
    */
    public function isSSL(): bool
    { return $this->ssl; }

    /**
     * Returns protocol
     *
     * @return string
     * @example "http"
    */
    public function getProtocol(): string
    { return $this->protocol; }

    /**
     * Returs origin (base-url)
     *
     * @return string
     * @example: "http://localhost"
     */
    public function getOrigin(): string
    { return $this->origin; }

    /**
     * Returns full path
     *
     * @return stirng
     * @example: "http://localhost/some_route?param=value"
     */
    public function getFull(): string
    { return $this->full; }

    /**
     * Returns port
     *
     * @return int - If ssl || 443, then 0
    */
    public function getPort(): int
    { return $this->port; }

    /**
     * Returns host
     *
     * @return string
     * @example: "localhost"
    */
    public function getHost(): string
    { return $this->host; }

    /**
     * Returns request uri
     *
     * @return string
     * @example: comments/get
     */
    public function getUri(): string
    { return $this->uri; }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Build Url instance using request-related data
     *
     * @return Url
     */
    public static function fromRequest(): IUrl
    {
        $output = new Url();

        $SERVER_PROTOCOL = strtolower($_SERVER['SERVER_PROTOCOL']);

        $output->ssl      = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
        $output->port     = (int)$_SERVER['SERVER_PORT'];
        $output->port     = (!$output->ssl && $output->port == 80) || ($output->ssl && $output->port == 443) ? '' : ":{$output->port}";
        $output->host     = USE_FORWARDED_HOST && isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : ( isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null );
        $output->host     = !empty($output->host) ? $output->host : "{$_SERVER['SERVER_NAME']}{$output->port}";
        $output->protocol = substr($SERVER_PROTOCOL, 0, strpos( $SERVER_PROTOCOL, '/' ) ) . ($output->ssl ? 's' : '');
        $output->origin   = "{$output->protocol}://{$output->host}";
        $output->uri      = $_SERVER['REQUEST_URI'];
        $output->full     = "{$output->origin}{$output->uri}";

        return $output;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
