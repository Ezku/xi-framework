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
 * @package     Xi_Auth
 * @subpackage  Xi_Auth_Adapter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Auth_Adapter_Abstract implements Xi_Auth_Adapter_Standard_Interface
{
    /**
     * @var string
     */
    protected $_identity;
    
    /**
     * @var string
     */
    protected $_credential;

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Xi_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Xi_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }
    
    /**
     * Create a Zend_Auth_Result for an {@link authenticate()} request. If no
     * parameters are provided, creates a result object indicating a successful
     * authentication. 
     * 
     * @param int $code
     * @param string|array $messages
     * @return Zend_Auth_Result
     */
    protected function _createResult($code = Zend_Auth_Result::SUCCESS, $messages = array('Authentication successful.'))
    {
        if (null === $this->_identity) {
            throw new Xi_Auth_Adapter_Exception("Cannot create authentication result when no identity set");
        }
        return new Zend_Auth_Result($code, $this->_identity, (array) $messages);
    }
}
