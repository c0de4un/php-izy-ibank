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
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * IRequest - Request behavior contract
 *
 * @version 1.0
*/
interface IRequest
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Search for GET-params
     *
     * @param string $key
     * @param any    $default
     *
     * @return int
     * @return string
     * @return bool
    */
    public function get( $key, $default = null );

    /**
     * Search for POST-params
     *
     * @param string $key
     * @param any    $default
     *
     * @return int
     * @return string
     * @return bool
    */
    public function post( $key, $default = null );

    /**
     * Returns Url
     *
     * @return IUrl
    */
    public function getUrl(): IUrl;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
