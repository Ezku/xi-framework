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
 * Parse factory definitions from configuration data
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Parser implements Zend_Filter_Interface 
{
    protected $_parsers = array(
        'Xi_Factory_Parser_Factory',
        'Xi_Factory_Parser_Class',
        'Xi_Factory_Parser_Callback',
        'Xi_Factory_Parser_Reference',
        'Xi_Factory_Parser_Registry'
    );

    /**
     * @param array $parsers
     */
    public function __construct($parsers = array())
    {
        $this->setParsers(array_merge($this->_parsers, $parsers));
    }

    /**
     * @param mixed configuration data
     * @return mixed|array
     */
    public function filter($config)
    {
        if (!(is_array($config) || $config instanceof Iterator || $config instanceof IteratorAggregate)) {
            return $config;
        }

        /**
         * Parse branches
         */
        $parsed = array();
        foreach ($config as $key => $value) {
            /**
             * Leave scalar values untouched
             */
            if (!is_scalar($value)) {
                $value = $this->filter($value);
            }

            $parsed[$key] = $value;
        }

        /**
         * Parse current node
         */
        foreach ($this->_parsers as $parser) {
            if ($parser->isValidConfig($parsed)) {
                return $parser->fromConfig($parsed);
            }
        }
        return $parsed;
    }


    /**
     * Add a parser to the list of factory parser classes to be used
     *
     * @param string factory parser class name or instance
     * @return void
     * @see Xi_Factory_Parser_Abstract
     */
    public function addParser($parser)
    {
        if (is_string($parser)) {
            $parser = new $parser;
        }
        $this->_parsers[] = $parser;
    }

    /**
     * Set factory parsers
     *
     * @param array instances or class names
     * @return Xi_Factory_Parser
     */
    public function setParsers($parsers)
    {
        foreach ($parsers as &$parser) {
            if (is_string($parser)) {
                $parser = new $parser;
            }
        }
        $this->_parsers = $parsers;
    }

    /**
     * Get factory parser instances
     *
     * @return array
     */
    public function getParsers()
    {
        return $this->_parsers;
    }
}


