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
class Xi_Factory_Configurable extends Xi_Factory_Abstract implements Xi_Factory_Configurable_Interface
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * @var string
     */
    protected $_argumentOptionKey = 'args';

    /**
     * Whether to retrieve arguments from options if no arguments are provided
     * in the second parameter of {@link get()}.
     *
     * @var boolean
     */
    protected $_enableArgumentsFromOptions = true;

    /**
     * @param array options
     * @return void
     */
    public function __construct($options)
    {
        $this->_options = $options;
        $this->init();
    }

    /**
     * Template method called on construction
     *
     * @return void
     */
    public function init()
    {}
    
    public function getArguments($args)
    {
        if (null === $args) {
            if ($this->_enableArgumentsFromOptions) {
                $args = $this->getArgumentsFromOptions();
            }
            $args = parent::getArguments($args);
        }

        return $args;
    }
    
    public function getArgumentsFromOptions()
    {
        $key = $this->_argumentOptionKey;
        if (isset($this->_options[$key])) {
            return (array) $this->_options[$key];
        }
    }

    public function getOption($name, $default = null)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        return $default;
    }
    
    public function hasOption($name)
    {
        return isset($this->_options[$name]);
    }
}