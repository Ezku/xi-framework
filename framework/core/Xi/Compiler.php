<?php
/**
 * Compiles the definitions of classes into
 *
 * TODO: Currently does not cache interfaces.
 *
 * @category    Xi
 * @package     Xi_Compiler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Compiler
{
    /**
     * @param array classes to compile
     * @return array compilation of the class definitions
     * @throws Xi_Exception if a given class is not found and can not be loaded
     */
    public function compile($classes)
    {
        $classes = array_unique($classes);

        /**
         * Collect class definitions
         */
        $definitions = array();
        foreach ($classes as $class) {

            if (!class_exists($class, false)) {
                throw new Xi_Exception('Could not compile class ' . $class . ' because it was not defined');
            }

            $definitions[$class] = $this->getDefinition($class);
        }

        return $definitions;
    }

    /**
     * @param string class name
     * @return string class definition in PHP
     */
    public function getDefinition($class)
    {
        $reflection    = new ReflectionClass($class);
        $file          = $reflection->getFileName();
        $start         = $reflection->getStartLine()-1;
        $end           = $reflection->getEndLine();
        $file          = file($file);
        $definition    = array_splice($file, $start, $end-$start);
        return implode("", $definition);
    }
}
