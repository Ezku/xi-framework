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
 * Describes an object capable of changing parameters in a request object to
 * forward execution to a new target
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Controller_Request_Redirection_Interface
{
    /**
     * Set target module name
     *
     * @param string $module
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setModule($module);
    
    /**
     * Set target controller name
     *
     * @param string $controller
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setController($controller);
    
    /**
     * Set target action name
     *
     * @param string $action
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setAction($action);
    
    /**
     * Get target module name
     *
     * @return string
     */
    public function getModule();
    
    /**
     * Get target controller name
     *
     * @return string
     */
    public function getController();
    
    /**
     * Get target action name
     *
     * @return string
     */
    public function getAction();
    
    /**
     * Apply redirection to request
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function apply(Zend_Controller_Request_Abstract $request);
}
