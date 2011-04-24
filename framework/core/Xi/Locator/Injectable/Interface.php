<?php
/**
 * @category    Xi
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Locator_Injectable_Interface
{
    /**
     * @param Xi_Locator
     * @return object this instance
     */
    public function setLocator($locator);
}

