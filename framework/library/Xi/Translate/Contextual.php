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
 * @package     Xi_Translate
 * @subpackage  Xi_Translate_Contextual
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Translate_Contextual implements Xi_Translate_Contextual_Interface
{
    /**
     * @var array<Xi_Translate_Context_Interface>
     */
    protected $_contexts = array();
    
    /**
     * @return int the amount of contexts supported
     */
    public function count()
    {
        return count($this->_contexts);
    }
    
    /**
     * @param Xi_Translate_Contextual_Interface $context
     */
    public function addContext($context)
    {
        $this->_contexts[] = $context;
    }
    
    /**
     * @param string $subject
     * @return false|Xi_Translate_Context_Interface
     */
    public function context($subject)
    {
        foreach ($this->_contexts as $context) {
            if ($context->appliesTo($subject)) {
                return $context;
            }
        }
        return false;
    }
    
    /**
     * @param Traversable $data
     * @param string|Zend_Locale $locale
     * @return array translated data
     */
    public function translate($data, $locale = null)
    {
        $result = array();
        foreach ($data as $key => $value) {
            if ((null !== $value) && is_scalar($value) && ($context = $this->context($key))) {
                $value = $context->translate($value, $locale);
            } elseif (is_array($value) || ($value instanceof Traversable)) {
                $value = $this->translate($value, $locale);
            }
            $result[$key] = $value;
        }
        return $result;
    }
}
