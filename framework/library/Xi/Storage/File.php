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
 * @category    Xi
 * @package     Xi_Storage
 * @subpackage  Xi_Storage_File
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Storage_File implements Xi_Storage_Interface 
{
    /**
     * @var SplFileInfo
     */
    protected $_file;
    
    /**
     * Provide either a file name or an SplFileInfo object referring to a file
     * 
     * @param SplFileInfo|string $file
     * @return void
     */
    public function __construct($file)
    {
        if (!$file instanceof SplFileInfo) {
            $file = new SplFileInfo($file);
        }
        if ($file->isDir()) {
            $error = sprintf("File %s can not be a directory", (string) $file);
            throw new Xi_Storage_Exception($error);
        }
        $this->_file = $file;
    }
    
    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->_file;
    }
    
    /**
     * Check whether file can be read
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return !$this->getFile()->isReadable();
    }
    
    /**
     * Read contents of file
     *
     * @return mixed
     */
    public function read()
    {
        $file = $this->getFile();
        if (!$file->isReadable()) {
            $error = sprintf("File %s is not readable", (string) $file);
            throw new Xi_Storage_Exception($error);
        }
        return unserialize(file_get_contents((string) $file));
    }
    
    /**
     * Write contents to file
     * 
     * @param mixed $contents
     */
    public function write($contents)
    {
        $file = $this->getFile();
        if ($file->isFile() && !$file->isWritable()) {
            $error = sprintf("File %s is not writable", (string) $file);
            throw new Xi_Storage_Exception($error);
        }
        file_put_contents((string) $file, serialize($contents));
    }
    
    /**
     * Delete file
     *
     * @return void
     */
    public function clear()
    {
        unlink((string) $this->getFile());
    }
}
