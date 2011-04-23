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
 * @package     Xi_Doctrine
 * @subpackage  Xi_Doctrine_Manager
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Doctrine_Manager
{
    /**
     * @var Xi_Doctrine_Manager
     */
    protected static $_instance;
    
    /**
     * @var array<Doctrine_Connection>
     */
    protected $_defaultConnectionStack = array();
    
    /**
     * @return Xi_Doctrine_Manager
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * @param Xi_Doctrine_Manager $instance
     * @return Xi_Doctrine_Manager
     */
    public static function setInstance($instance)
    {
        return self::$_instance = $instance;
    }
    
    /**
     * Retrieves a Doctrine_Connection by name, sets it as current and pushes
     * the old current onto a stack. The old current connection can be restored
     * as current with a call to {@link restoreCurrentConnection()}.
     * 
     * @param string $name
     * @return Doctrine_Connection
     */
    public function getConnectionAsCurrent($name)
    {
        $manager = Doctrine_Manager::getInstance();
        
        $conn = $manager->getConnection($name);
        $this->_defaultConnectionStack[] = $manager->getCurrentConnection();
        $manager->setCurrentConnection($conn->getName());
        return $conn;
    }
    
    /**
     * Restores the latest Doctrine_Connection instance from the connection
     * stack as the current connection and returns it.
     * 
     * @return Doctrine_Connection
     * @throws Xi_Doctrine_Manager_Exception if the stack is empty
     */
    public function restoreCurrentConnection()
    {
        $conn = array_pop($this->_defaultConnectionStack);
        if (empty($conn)) {
            $error = 'Unmatching call to ' . __METHOD__ . ': no connection to restore as current';
            throw new Xi_Doctrine_Manager_Exception($error);
        }
        Doctrine_Manager::getInstance()->setCurrentConnection($conn->getName());
        return $conn;
    }
}
