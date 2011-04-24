<?php
/**
 * Collects the names of declared classes
 * 
 * @category    Xi
 * @package     Xi_Compiler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Compiler_ClassCollector
{
    protected $_prefixes = array();
    protected $_classes = array();
    protected $_started = false;
    
    public function __construct(array $prefixes = array())
    {
        $this->_prefixes = $prefixes;
    }
    
    public function startCollect()
    {
        $this->_started = true;
        $this->_classes = get_declared_classes();
        return $this;
    }
    
    public function endCollect()
    {
        if ($this->_started) {
            $this->_classes = array_diff(get_declared_classes(), $this->_classes);
            $this->_started = false;
        }
        return $this;
    }
    
    public function getClasses($prefixes = null)
    {
        if ($this->_started) {
            $this->endCollect();
        }
        
        if (null === $prefixes) {
            $prefixes = $this->_prefixes;
        }
        
        if (empty($prefixes)) {
            return $this->_classes;
        }
        
        $classes = array();
        foreach ($this->_classes as $class) {
            foreach ($prefixes as $prefix) {
                if (0 === strpos($class, $prefix)) {
                    $classes[] = $class;
                    break;
                }
            }
        }
        
        return $classes;
    }
}


