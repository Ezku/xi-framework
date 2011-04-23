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
 * @package     Xi_Cache
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Cache_File implements Xi_Cache
{
    protected $_file;
    protected $_doSerialize;
    protected $_timeToLive;

    /**
     * @param string path to file
     * @param null|boolean disable/enable automatic data serialization
     * @param null|int time to live (false to disable cache expiration)
     */
    public function __construct($file, $doSerialize = false, $timeToLive = 3600)
    {
        $this->_file = $file;
        $this->_doSerialize = $doSerialize;
        $this->_timeToLive = $timeToLive;
    }

    public function isValid()
    {
        return is_readable($this->_file)
               && (false === $this->_timeToLive || ((time() - filemtime($this->_file)) < $this->_timeToLive));
    }

    public function load()
    {
        $data = file_get_contents($this->_file);
        if ($this->_doSerialize) {
            $data = unserialize($data);
        }
        return $data;
    }

    public function write($data)
    {
        if ($this->_doSerialize) {
            $data = serialize($data);
        }
        return file_put_contents($this->_file, $data);
    }

    public function getFilename()
    {
        return $this->_file;
    }
}

