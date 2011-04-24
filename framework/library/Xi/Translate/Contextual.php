<?php
/**
 * @category    Xi
 * @package     Xi_Translate
 * @subpackage  Xi_Translate_Contextual
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
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
