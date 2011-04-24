<?php
/**
 * @category    Xi_Test
 * @package     Xi
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_AllTests
{
    public static function main(array $defaults = array())
    {
        $arguments = $_REQUEST += $defaults;
        $suite = self::suite(isset($arguments['dir']) ? $arguments['dir'] : dirname(__FILE__));
        PHPUnit_TextUI_TestRunner::run($suite, $arguments);
    }

    public static function suite($dir = null)
    {
        if (null === $dir) {
            $dir = dirname(__FILE__);
        }
        $collector = new PHPUnit_Runner_IncludePathTestCollector(array($dir));
        $tests     = $collector->collectTests();

        $suite     = new PHPUnit_Framework_TestSuite('Xi Framework');
        $suite->addTestFiles($tests);

        return $suite;
    }
}