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

require_once( 'EResponseStatuses.php' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * IResponse - response behavior contract
 *
 * @version 1.0
*/
interface IResponse
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

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
    public function Status( bool $status ): IResponse;

    /**
     * Set header value
     *
     * @param string $key
     * @param string $value
     *
     * @return IResponse
    */
    public function Header( $key, $value ): IResponse;

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
    public function JSON( $key, $value ): IResponse;

    /**
     * Reset Response state.
     *
     * @return IResponse
    */
    public function Reset(): IResponse;

    /**
     * Commit response
     *
     * @return void
    */
    public function Commit(): void;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
