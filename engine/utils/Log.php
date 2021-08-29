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

namespace Izy\Utils;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TYPES
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

/**
 * Log - simple file logger
 *
 * @version 1.0
*/
final class Log
{

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // CONSTANTS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var String root-dir */
    private $ROOT_DIR;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // FIELDS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /** @var Log */
    private static $instance = null;

    /** @var Array[String] cached file names. */
    private $file_names = [];

    /** @var Array[File] */
    private $files = [];

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PUBLIC
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * @brief
     *
     * @param boolean [$alloc = true]
     * @param string  [$dir = null ]
     * @return FLogger||NULL
    */
    public static function getInstance( bool $alloc = true, $dir = null )
    {
        if ( $alloc && empty(self::$instance) ) {
            self::$instance = new Log( $dir );
        }

        return self::$instance;
    }

    /**
     * Set new Root-dir
     *
     * @param String $dir
    */
    public function setRootDir( string $dir )
    {
        if ( $dir[strlen($dir) - 1] !== '/' ) {
            $dir .= '/';
        }

        $this->ROOT_DIR = $dir;
    }

    /**
     * @brief
     * Called when script ends
    */
    public function handleShutdown(): void
    {
        $instance = self::getInstance( false );
        $instance->close();
    }

    /**
     * Print INFO-message
     *
     * @return void
    */
    public static function info( $msg, $context = 'core', $dir = 'log' )
    {
        self::print( "GOOD: {$msg}", $context, $dir );
    }

    /**
     * Print VERBOSE-message
     *
     * @return void
    */
    public static function verbose( $msg, $context = 'core', $dir = 'log' )
    {
        self::print( "VERBOSE: .{$msg}", $context, $dir );
    }

    /**
     * Print WARNING-message
     *
     * @return void
    */
    public static function warning( $msg, $context = 'core', $dir = 'log' )
    {
        self::print( "WARNING: {$msg}", $context, $dir );
    }

    /**
     * Print ERROR-message
     *
     * @return void
    */
    public static function error( $msg, $context = 'core', $dir = 'log' ): void
    {
        self::print( "FATAL_ERROR: {$msg}", $context, $dir );
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // METHODS.PRIVATE
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Append output for file
     *
     * @param String $msg
     * @param String $context = 'core'
     * @param String $dir = 'log'
     *
     * @return void
    */
    private static function print( $msg, $context = 'core', $dir = 'log' ): void
    {
        $instance = self::getInstance();
        $file     = $instance->getFile( $context, $dir );
        $dt_mark  = date( 'Y-m-d H:i:s' );
        fwrite( $file, $dt_mark.PHP_EOL.$msg.PHP_EOL );
    }

    /**
     * Returns appropriate File instance for context & dir
     *
     * @param String $context
     * @param String $dir
     *
     * @return File
    */
    private function getFile( $context, $dir )
    {
        if ( empty($this->files[$context]) ) {
            $this->verifyDirs( $dir );
            $file_name = $this->ROOT_DIR . $dir . '/' . $this->getLogFile_Name( $context ) . '.log';

            return $this->files[$context] = fopen( $file_name, 'a' );
        }

        return $this->files[$context];
    }

    /**
     * Build date-time mark for file-name
     *
     * @return String
    */
    private function getDateMark()
    { return date('Y_m_d', time() ); }

    /**
     * Build log-file name
     *
     * @param String $context
     *
     * @return String
    */
    private function getLogFile_Name( $context )
    {
        if ( empty($this->file_names[$context]) ) {
            $output = $this->file_names[$context] = $context.'_'.$this->getDateMark();
        } else {
            $output = $this->file_names[$context];
        }

        return $output;
    }

    /**
     * @param string [$dir = null]
    */
    private function __constructor( string $dir= null )
    {
        $this->ROOT_DIR = $dir ?? getcwd();

        register_shutdown_function( [$this, 'handleShutdown'] );
    }

    /**
     * Build missing dirs in the path
     *
     * @param String $path
     *
     * @return void
    */
    private function verifyDirs( $path ): void
    {
        if ( !file_exists($this->ROOT_DIR . $path) ) {
            try {
                mkdir( $this->ROOT_DIR . $path, 0777, true );
            } catch( \Exception $exception ) {
                /* - void - */
            } finally { /* - void - */ }
        }
    }

    /**
     * Close/flush output files
     *
     * @return void
    */
    private function close(): void
    {
        if ( !empty($this->files) ) {
            foreach( $this->files as &$file )
            {
                fclose( $file );
            }
        }
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

};

// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
