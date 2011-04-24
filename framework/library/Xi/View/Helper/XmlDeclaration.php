<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_XmlDeclaration extends Zend_View_Helper_Abstract
{
    /**
     * Returns an XML declaration either with a default encoding attribute
     * retrieved from the view or the encoding provided as an argument
     *
     * @param string $encoding
     * @return string
     */
    public function xmlDeclaration($encoding = null)
    {
        if (null === $encoding) {
            $encoding = $this->view->getEncoding();
        }
        
        return '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n";
    }
}
