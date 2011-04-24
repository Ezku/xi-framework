<?php
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
