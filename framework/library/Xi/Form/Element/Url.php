<?php
/**
 * @category    Xi
 * @package     Xi_Form
 * @subpackage  Xi_Form_Element
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Form_Element_Url extends Zend_Form_Element_Text
{
    /**
     * @see Zend_Form_Element::init()
     * @return void
     */
    public function init()
    {
        $this->addValidator(new Xi_Validate_Url);
        $this->addFilter(new Xi_Filter_Url);
        $this->addValidator(new Zend_Validate_StringLength(4, 2000));
    }
}
