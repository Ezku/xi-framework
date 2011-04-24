<?php
/**
 * @category    Xi
 * @package     Xi_Compiler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Compiler_Job extends Xi_Scheduler_Job
{
    /**
     * @var Xi_Compiler_ClassCollector
     */
    protected $_collector = array();

    /**
     * @param Xi_Storage_File
     */
    protected $_storage;

    /**
     * @var Xi_Compiler
     */
    protected $_compiler;

    /**
     * Run compiler despite storage validity
     *
     * @var boolean
     */
    protected $_forceRun = false;

    /**
     * @param array prefixes of the classes to be compiled
     * @param Xi_Storage_Interface for storing the classes
     * @param null|Xi_Compiler
     */
    public function __construct(array $prefixes, Xi_Storage_Interface $storage, Xi_Compiler $compiler = null)
    {
        if (null === $compiler) {
            $compiler = new Xi_Compiler;
        }
        $this->_compiler    = $compiler;
        $this->_storage     = $storage;
        $this->_collector   = new Xi_Compiler_ClassCollector($prefixes);

        $this->_collector->startCollect();

        if ($storage->isEmpty()) {
            $storage->write('');
            $this->_forceRun = true;
        }
    }

    public function run(Xi_Scheduler $scheduler)
    {
        if ($this->_forceRun || $this->_storage->isEmpty()) {

            $compiler    = $this->_compiler;
            $classes     = $this->_collector->endCollect()->getClasses();
            $definitions = $compiler->compile($classes);

            /**
             * First pass: write definitions to file as they are. At this point
             * comments and whitespace are still preserved.
             */
            $this->_storage->write('<?php ' . implode("", $definitions) . ' ?>');

            /**
             * Second pass: get definitions from file with comments and whitespace
             * stripped, then write back.
             */
            $definitions = php_strip_whitespace($this->_storage->getFilename());
            $this->_storage->write($definitions);

        }
    }
}

