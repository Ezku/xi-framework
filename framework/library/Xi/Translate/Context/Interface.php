<?php
/**
 * @category    Xi
 * @package     Xi_Translate
 * @subpackage  Xi_Translate_Context
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Translate_Context_Interface
{
    /**
     * Check whether this context can be applied to $subject
     * 
     * @param string $context
     * @return boolean
     */
    public function appliesTo($subject);
    
    /**
     * @param string $subject
     * @return Xi_Translate_Context_Interface
     */
    public function setSubject($subject);
    
    /**
     * @return Zend_Translate
     */
    public function getTranslator();
    
    /**
     * @param Zend_Translate $translator
     * @return Xi_Translate_Context_Interface
     */
    public function setTranslator($translator);
    
    /**
     * @see Zend_Translate_Adapter::translate()
     * @param  string $messageId
     * @param  string|Zend_Locale $locale
     * @return string
     */
    public function translate($messageId, $locale = null);
}
