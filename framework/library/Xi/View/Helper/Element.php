<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_View_Helper_Element extends Xi_View_Helper
{
    /**
     * @var string
     */
    protected $_elementFormat = '<%1$s%2$s>%3$s</%1$s>';
    
    /**
     * @var string
     */
    protected $_emptyElementFormat = '<%1$s%2$s />';
    
    /**
     * Format the string for an HTML element.
     * 
     * @param string element tag name (eg. "em")
     * @param string|false|null element contents; false for an empty element
     * @param array|null element attributes
     * @return string
     */
    public function element($element, $content = false, $attributes = array())
    {
        if (false === $content) {
            return sprintf($this->_emptyElementFormat, $element, $this->_formatAttributes($attributes));
        } else {
            return sprintf($this->_elementFormat, $element, $this->_formatAttributes($attributes), $content);
        }
    }
    
    /**
     * Format attributes for an HTML element
     * 
     * @param array
     * @return string
     */
    protected function _formatAttributes(array $attribs)
    {
        $xhtml = '';
        foreach ((array) $attribs as $key => $val) {
            $key = $this->view->escape($key);
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            $val = $this->view->escape($val);
            $xhtml .= " $key=\"$val\"";
        }
        return $xhtml;
    }
}

