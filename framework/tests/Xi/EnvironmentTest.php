<?php
/**
 * @category    Xi_Test
 * @package     Xi_Environment
 * @group       Xi_Environment
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_EnvironmentTest extends PHPUnit_Framework_TestCase
{
    function tearDown()
    {
        Xi_Environment::set('dev');
    }

    function testDefaultEnvironmentIsDev()
    {
        $this->assertEquals('dev', Xi_Environment::get());
    }

    function testEnvironmentCanBeSet()
    {
        Xi_Environment::set('asdf');
        $this->assertEquals('asdf', Xi_Environment::get());
    }

    function testEnvironmentCanBeMatchedAgainst()
    {
        $this->assertTrue(Xi_Environment::is('dev'));
    }
}

