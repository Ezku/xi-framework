<?php
/**
 * @category    Xi
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Locator_Injector
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;
    
    /**
     * Provide a Xi_Locator instance to inject objects with
     *
     * @param Xi_Locator $locator
     * @return void
     */
    public function __construct($locator)
    {
        $this->_locator = $locator;
    }

    /**
     * Recursively inject locator to Injectables in a collection
     *
     * @param array
     * @return void
     */
    public function inject($target)
    {
        if ($target instanceof Xi_Locator_Injectable_Interface) {
            $this->_injectLeaf($target);
        } elseif (is_array($target) || $target instanceof Iterator | $target instanceof IteratorAggregate) {
            $this->_injectBranch($target);
        }
    }

    /**
     * Injection procedure: handle leaf node
     *
     * @param Xi_Locator_Injectable_Interface
     * @return false
     */
    protected function _injectLeaf($target)
    {
        $target->setLocator($this->_locator);
    }

    /**
     * Injection procedure: handle branch node
     *
     * @param array|Xi_Array|Xi_Locator
     */
    protected function _injectBranch($target)
    {
        if ($target instanceof Xi_Locator) {
            $target = $target->getIterator(false);
        }
        
        foreach ($target as $t) {
            $this->inject($t);
        }
    }
}
