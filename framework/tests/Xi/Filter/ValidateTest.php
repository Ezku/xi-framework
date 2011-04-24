<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_ValidateTest extends Xi_Filter_TestCase 
{
    public function testFiltersValueOnInvalid()
    {
        $validator = $this->getMock('Zend_Validate_Interface');
        $validator->expects($this->once())->method('isValid')->with($this->equalTo('foo'))->will($this->returnValue(false));
        
        $filter = new Xi_Filter_Validate($this->getFilterMock(), $validator);
        $this->assertEquals('bar', $filter->filter('foo'));
    }
    
    public function testReturnsOriginalValueOnValid()
    {
        $validator = $this->getMock('Zend_Validate_Interface');
        $validator->expects($this->once())->method('isValid')->with($this->equalTo('foo'))->will($this->returnValue(true));
        
        $filter = new Xi_Filter_Validate($this->getFilterMock(false), $validator);
        $this->assertEquals('foo', $filter->filter('foo'));
    }
}
