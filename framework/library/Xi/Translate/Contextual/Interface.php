<?php
/**
 * @category    Xi
 * @package     Xi_Translate
 * @subpackage  Xi_Translate_Contextual
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Translate_Contextual_Interface extends Countable
{
    
    /**
     * @param Xi_Translate_Contextual_Interface $context
     */
    public function addContext($context);
    
    /**
     * @param string $subject
     * @return Xi_Translate_Context_Interface
     * @throws Xi_Translate_Contextual_Exception if subject is not supported
     */
    public function context($subject);
    
    /**
     * @param Traversable $data
     * @param string|Zend_Locale $locale
     * @return array translated data
     */
    public function translate($data, $locale = null);
}
