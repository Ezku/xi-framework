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
 * Factory for a Doctrine database connection
 *
 * @category    Xi
 * @package     Xi_Doctrine
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Doctrine_Connection_Factory extends Xi_Factory
{
    public function create($connectionName = null, $setCurrent = null)
    {
        if (null === $connectionName) {
            $connectionName = 'default';
        }
        if (null === $setCurrent) {
            $setCurrent = true;
        }

        $manager = Doctrine_Manager::getInstance();

        if (!$manager->contains($connectionName)) {
            $config = $this->getConfig();
            if (!isset($config->$connectionName)) {
                $message = sprintf('Connection "%s" not found in configuration', $connectionName);
                throw new Xi_Exception($message);
            }
            return $manager->openConnection($config->$connectionName, $connectionName, $setCurrent);
        }

        if ($setCurrent) {
            $manager->setCurrentConnection($connectionName);
        }
        return $manager->getConnection($connectionName);
    }

    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        if ($this->hasOption('config')) {
            $config = $this->getOption('config');
        } else {
            $config = $this->_locator->config->database;
        }

        $config = new Xi_Config_Filter_Inflector($config, $this->getInflector());
        return $config;
    }
    
    /**
     * @return Xi_Filter_Inflector_Recursive
     */
    public function getInflector()
    {
        return clone $this->_locator->config->paths->doctrine->getFilter();
    }
}

