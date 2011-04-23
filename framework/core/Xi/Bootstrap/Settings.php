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

Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Bootstrap_Settings extends Xi_Scheduler_Job
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $locator->config->settings = $locator->config->load('settings');
    }

    public function run(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $settings = $locator->config->settings;

        /**
         * Error reporting
         */
        if (isset($settings->errorReporting)) {
            error_reporting($settings->errorReporting);
        }

        /**
         * Error handler
         */
        if (isset($settings->errorHandler)) {
            $handler = $settings->errorHandler;
            if (is_object($handler)) {
                set_error_handler($handler->toArray());
            } else {
                set_error_handler($handler);
            }
        }

        /**
         * Exception handler
         */
        if (isset($settings->exceptionHandler)) {
            $handler = $settings->exceptionHandler;
            if (is_object($handler)) {
                set_exception_handler($handler->toArray());
            } else {
                set_exception_handler($handler);
            }
        }
    }
}
