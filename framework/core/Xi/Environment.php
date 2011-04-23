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
 * @package     Xi_Environment
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Environment
{
    const ENV_DEVELOPMENT = 'dev';
    const ENV_PRODUCTION  = 'prod';
    const ENV_TESTING     = 'test';

    /**
     * Current environment
     *
     * @var string
     */
    protected static $_environment = self::ENV_DEVELOPMENT;

    /**
     * Set environment
     *
     * @param string new environment
     * @return string environment
     */
    public static function set($new)
    {
        return self::$_environment = $new;
    }

    /**
     * Get environment
     *
     * @return string
     */
    public static function get()
    {
        return self::$_environment;
    }

    /**
     * Check whether environment is set to one of the arguments provided
     *
     * @param string environment
     * ...
     * @return boolean true if current environment matches any parameter
     */
    public static function is($env)
    {
        if (func_num_args() > 1) {
            foreach (func_get_args() as $arg) {
                if (self::$_environment === $arg) {
                    return true;
                }
            }
            return false;
        }
        return self::$_environment == $env;
    }

    /**
     * @return string
     */
    public static function getFrameworkDirectory()
    {
        return dirname(dirname(dirname(__FILE__)));
    }
}

