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
 * Note on the use of exceptions:
 *
 * To preserve the intent of an exception hierarchy, do not extend from
 * Xi_Exception directly if a Zend equivalent is available. For example, should
 * classes in the Controller package require an exception of their own, don't do
 * this:
 *
 * <code>class Xi_Controller_Exception extends Xi_Exception {}</code>
 *
 * But instead do this:
 *
 * <code>class Xi_Controller_Exception extends Zend_Controller_Exception {}
 * </code>
 *
 * Furthermore, unless there is a particular reason Xi and Zend versions of the
 * same exception should be considered separately, when catching an exception
 * use a type hint for the Zend version and not the Xi one. Don't do this:
 *
 * <code>try {
 *    $controller->run();
 * } catch(Xi_Controller_Exception $e) { ... }</code>
 *
 * But instead do this:
 *
 * <code>try {
 *    $controller->run();
 * } catch(Zend_Controller_Exception $e) { ... }</code>
 *
 * @category    Xi
 * @package     Xi_Exception
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Exception extends Zend_Exception
{
    /**
     * Create exception from error data
     *
     * @param int error code
     * @param string error message
     * @param string file name
     * @param int line number
     * @throws Xi_Exception
     */
    public static function handleError($code, $string, $file, $line)
    {
        if (!(error_reporting() & $code)) {
            return;
        }

        $e = new self($string, $code);
        $e->line = $line;
        $e->file = $file;
        throw $e;
    }
}
