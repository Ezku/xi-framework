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
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Factory_Abstract implements Xi_Factory_Interface
{
    /**
     * Modify the default behaviour of {@link mapCreationArguments()}: if
     * enabled, arguments will be passed as a single array in the first
     * parameter; otherwise the arguments will be expanded into parameters
     *
     * @var boolean
     */
    protected $_mapArgumentsToArray = false;

    public function get($args = null)
    {
        $args = $this->getArguments($args);
        $args = $this->mapCreationArguments($args);
        return call_user_func_array(array($this, 'create'), $args);
    }
    
    /**
     * Provided the $args passed to {@link get()}, retrieve an array of arguments to use.
     *
     * @param null|array $args
     * @return array
     */
    public function getArguments($args)
    {
        if (null === $args) {
            $args = $this->getDefaultArguments();
        }
        return $args;
    }

    /**
     * Get default creation arguments. Used if there are no arguments available.
     *
     * @return mixed
     */
    public function getDefaultArguments()
    {
        return array();
    }

    /**
     * Maps $args to arguments to {@link create()}.
     *
     * @param null|array
     * @return mixed
     */
    public function mapCreationArguments($args)
    {
        if ($this->_mapArgumentsToArray) {
            return array($args);
        }
        return $args;
    }
}

