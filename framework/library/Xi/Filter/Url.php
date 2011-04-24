<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Url implements Zend_Filter_Interface
{
    /**
     * @var string 
     */
    protected $_scheme;
    
    /**
     * @param string $scheme
     */
    public function __construct($scheme = 'http')
    {
        $this->_scheme = $scheme;
    }
    
    /**
     * Attempts to transform an incomplete Uri into a valid one
     * 
     * @param mixed $value
     * @return string
     */
    public function filter($value)
    {
        if (!is_string($value) || empty($value)) {
            return '';
        }
        
        if (Zend_Uri::check($value)) {
            return $value;
        }
        
        $value = $this->_scheme . '://' . $value;
        if (Zend_Uri::check($value)) {
            return $value;
        }
        
        return '';
    }
}
