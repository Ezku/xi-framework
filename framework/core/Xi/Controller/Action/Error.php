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
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Action_Error extends Xi_Controller_Action
{
    public function errorAction()
    {
        $errors       = $this->_getParam('error_handler');
        $viewRenderer = $this->_helper->getHelper('ViewRenderer');
        $request      = $this->getRequest();

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                $viewRenderer->setScriptAction('controllerNotFound');
                $request->setActionName('controllerNotFound');
                return $this->controllerNotFoundAction();
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $viewRenderer->setScriptAction('actionNotFound');
                $request->setActionName('actionNotFound');
                return $this->actionNotFoundAction();
            default:
                $viewRenderer->setScriptAction('unknown');
                $request->setActionName('unknown');
                return $this->unknownAction();
        }
    }
    
    public function forbiddenAction()
    {
        $this->getResponse()->setHttpResponseCode(403);
    }

    public function controllerNotFoundAction()
    {
        $this->getResponse()->setHttpResponseCode(404);
    }

    public function actionNotFoundAction()
    {
        $this->getResponse()->setHttpResponseCode(404);
    }

    public function unknownAction()
    {
        $this->getResponse()->setHttpResponseCode(500);
    }
}

