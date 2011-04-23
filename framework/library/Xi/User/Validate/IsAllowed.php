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
 * @package     Xi_User
 * @subpackage  Xi_User_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User_Validate_IsAllowed extends Zend_Validate_Abstract
{
    const ACCESS_DENIED = 'accessDenied';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ACCESS_DENIED => 'Access not allowed'
    );
    
    /**
     * @var Xi_Acl_Action_Interface
     */
    protected $_action;
    
    /**
     * @param Xi_Acl_Action_Interface $action
     */
    public function __construct($action)
    {
        $this->_action = $action;
    }
    
    /**
     * @return Xi_Acl_Action_Interface
     */
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * @return Xi_User
     */
    public function getUser()
    {
        return Xi_User::getInstance();
    }
    
    /**
     * Check whether the action retrieved from {@link getAction()} is allowed
     * for the user from {@link getUser()}.
     * 
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if ($this->getUser()->isAllowed($this->getAction())) {
            return true;
        }
        $this->_error(self::ACCESS_DENIED);
        return false;
    }
}
