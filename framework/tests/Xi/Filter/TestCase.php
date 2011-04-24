<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_TestCase extends PHPUnit_Framework_TestCase 
{
    /**
     * @param boolean expects filter to be called?
     * @return Zend_Filter_Interface
     */
    public function getFilterMock($expect = true)
    {
        $filter = $this->getMock('Zend_Filter_Interface');
        if ($expect) {
            $filter->expects($this->once())->method('filter')->with($this->equalTo('foo'))->will($this->returnValue('bar'));
        } else {
            $filter->expects($this->never())->method('filter');
        }
        return $filter;
    }
    
    public function testMockFilterAcceptsFooAndReturnsBar()
    {
        $this->assertEquals('bar', $this->getFilterMock()->filter('foo'));
    }
}
