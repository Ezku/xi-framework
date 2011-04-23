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
 * @subpackage  Xi_Translate_Context
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Translate_Context implements Xi_Translate_Context_Interface
{
    /**
     * @var string
     */
    protected $_subject;
    
    /**
     * @var Zend_Translate_Adapter
     */
    protected $_translator;
    
    /**
     * @param string $subject
     * @return Xi_Translate_Context_Interface
     */
    public static function createWithSubject($subject)
    {
        $context = new self;
        $context->setSubject($subject);
        return $context;
    }
    
    /**
     * Check whether this context can be applied to $subject
     * 
     * @param string $context
     * @return boolean
     */
    public function appliesTo($subject)
    {
        return $this->_subject == $subject;
    }
    
    /**
     * @param string $subject
     * @return Xi_Translate_Context_Interface
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }
    
    /**
     * @return Zend_Translate_Adapter
     */
    public function getTranslator()
    {
        return $this->_translator;
    }
    
    /**
     * @param Zend_Translate_Adapter $translator
     * @return Xi_Translate_Context_Interface
     */
    public function setTranslator($translator)
    {
        $this->_translator = $translator;
        return $this;
    }
    
    /**
     * @see Zend_Translate_Adapter::translate()
     * @param  string $messageId
     * @param  string|Zend_Locale $locale
     * @return string
     */
    public function translate($messageId, $locale = null)
    {
        return isset($this->_translator)
            ? $this->_translator->translate($messageId, $locale)
            : $messageId;
    }
}
