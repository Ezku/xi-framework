<?php
/**
 * @category    Xi
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Locator_Injectable implements Xi_Locator_Injectable_Interface
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;

    /**
     * @param Xi_Locator
     * @return object this instance
     */
    public function setLocator($locator)
    {
        $this->_locator = $locator;
        return $this;
    }
}
