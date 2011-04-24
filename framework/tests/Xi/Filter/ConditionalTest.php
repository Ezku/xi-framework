<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_ConditionalTest extends PHPUnit_Framework_TestCase
{
    public function testCallsSuccessFilterWhenValid()
    {
        $validator = $this->getMock('Zend_Validate_Interface');
        $validator->expects($this->once())->method('isValid')->with('foo')->will($this->returnValue(true));
        
        $success = $this->getMock('Zend_Filter_Interface');
        $success->expects($this->once())->method('filter')->with('foo')->will($this->returnValue('bar'));
        
        $failure = $this->getMock('Zend_Filter_Interface');
        $failure->expects($this->never())->method('filter');
        
        $conditional = new Xi_Filter_Conditional($validator, $success, $failure);
        $this->assertEquals('bar', $conditional->filter('foo'));
    }
    
    public function testCallsFailureFilterWhenInvalid()
    {
        $validator = $this->getMock('Zend_Validate_Interface');
        $validator->expects($this->once())->method('isValid')->with('foo')->will($this->returnValue(false));
        
        $success = $this->getMock('Zend_Filter_Interface');
        $success->expects($this->never())->method('filter');
        
        $failure = $this->getMock('Zend_Filter_Interface');
        $failure->expects($this->once())->method('filter')->with('foo')->will($this->returnValue('bar'));
        
        $conditional = new Xi_Filter_Conditional($validator, $success, $failure);
        $this->assertEquals('bar', $conditional->filter('foo'));
    }
}

