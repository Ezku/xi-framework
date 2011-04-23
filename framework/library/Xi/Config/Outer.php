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
 * Decorates a config
 *
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Outer extends Xi_Config implements Xi_Config_Aggregate
{
    /**
     * @var Zend_Config
     */
    protected $_config;

    /**
     * @param array|Zend_Config
     * @return void
     */
    public function __construct($config)
    {
        if (!$config instanceof Zend_Config) {
            $config = parent::createBranch($config);
        }
        $this->_config = $config;
    }

    /**
     * Retrieve inner Zend_Config object
     *
     * @return Zend_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    public function createBranch($array)
    {
        return $this->_config->createBranch($array);
    }

    public function isReadOnly()
    {
        return $this->_config->isReadOnly();
    }

    public function setReadOnly()
    {
        return $this->_config->setReadOnly();
    }

    public function getSectionName()
    {
        return $this->_config->getSectionName();
    }

    public function areAllSectionsLoaded()
    {
        return $this->_config->areAllSectionsLoaded();
    }

    public function get($name, $default = null)
    {
        return $this->_config->get($name, $default);
    }
    
    public function toArray()
    {
        return $this->_config->toArray();
    }

    public function __set($name, $value)
    {
        return $this->_config->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->_config->$name);
    }

    public function __unset($name)
    {
        unset($this->_config->$name);
    }

    public function rewind()
    {
        return $this->_config->rewind();
    }

    public function next()
    {
        return $this->_config->next();
    }

    public function current()
    {
        return $this->_config->current();
    }

    public function valid()
    {
        return $this->_config->valid();
    }

    public function key()
    {
        return $this->_config->key();
    }
}
