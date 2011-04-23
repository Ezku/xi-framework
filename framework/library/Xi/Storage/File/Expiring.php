<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * A file storage with an expiry time
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @subpackage  Xi_Storage_File
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Storage_File_Expiring extends Xi_Storage_File
{
    /**
     * @var int
     */
    protected $_timeToLive;
    
    /**
     * Provide either a file name or an SplFileInfo object referring to a file
     * 
     * @param SplFileInfo|string $file
     * @param int $timeToLive
     * @return void
     */
    public function __construct($file, $timeToLive = 3600)
    {
        parent::__construct($file);
        $this->_timeToLive = $timeToLive;
    }
    
    /**
     * Get time to live in seconds
     * 
     * @return int
     */
    public function getTimeToLive()
    {
        return $this->_timeToLive;
    }
    
    /**
     * Get last modification timestamp
     * 
     * @return int|false
     */
    public function getLastModified()
    {
        return $this->getFile()->getMTime();
    }
    
    /**
     * Check whether file is empty or has timed out
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return parent::isEmpty() || $this->isTimedOut();
    }
    
    /**
     * Check whether file has timed out
     *
     * @return boolean
     */
    public function isTimedOut()
    {
        return 0 > (time() - $this->getTimeToLive() - $this->getLastModified());
    }
    
    /**
     * Read file contents or return null if file has timed out
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->isTimedOut()) {
            return null;
        }
        return parent::read();
    }
}
