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
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Instanceof extends Zend_Validate_Abstract
{
    const NOT_OBJECT = 'notObject';
    const NOT_VALID_INSTANCE = 'notValidInstance';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_OBJECT => 'The value was not an object',
        self::NOT_VALID_INSTANCE => 'The object was not an instance of %s'
    );
    
    /**
     * @var array valid classes
     */
    protected $_classes = array();

    /**
     * Provide a class name or an array of class names to check against
     * 
     * @param string|array $classes
     * @return void
     */
    public function __construct($classes)
    {
        $classes = (array) $classes;
        foreach ($classes as $class) {
            if (!class_exists($class) && !interface_exists($class)) {
                throw new Xi_Validate_Exception("Class $class does not exist");
            }
            $this->_classes[] = $class;
        }
        
        $last = array_pop($classes);
        $message = join(', ', $classes);
        $message .= ' or '.$last;
        $this->_messageTemplates[self::NOT_VALID_INSTANCE] = sprintf($this->_messageTemplates[self::NOT_VALID_INSTANCE], $message); 
    }

    /**
     * Check that value is an instance of one of the classes provided in the
     * constructor
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_object($value)) {
            $this->_error(self::NOT_OBJECT);
            return false;
        }
        
        foreach ($this->_classes as $class) {
            if ($value instanceof $class) {
                return true;
            }
        }
        
        $this->_error(self::NOT_VALID_INSTANCE);
        return false;
    }
}
