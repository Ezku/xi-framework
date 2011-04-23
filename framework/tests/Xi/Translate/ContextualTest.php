<?php
class Xi_Translate_ContextualTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Xi_Translate_Context_Interface
     */
    public function getContextMock()
    {
        return $this->getMock('Xi_Translate_Context_Interface',
            array('appliesTo', 'setTranslator', 'getTranslator', 'translate', 'setSubject'));
    }
    
    public function testShouldSupportNoContextByDefault()
    {
        $translate = new Xi_Translate_Contextual();
        $this->assertEquals(0, count($translate));
    }
    
    public function testShouldBeAbleToAddContext()
    {
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($this->getContextMock());
        $this->assertEquals(1, count($translate));
    }
    
    public function testShouldBeAbleToFindContext()
    {
        $context = $this->getContextMock();
        $context->expects($this->once())->method('appliesTo')->with($this->equalTo('foo'))->will($this->returnValue(true));
        
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($context);
        $this->assertSame($context, $translate->context('foo'));
    }
    
    public function testShouldReturnFalseWhenNoContextCanBeFound()
    {
        $context = $this->getContextMock();
        $context->expects($this->once())->method('appliesTo')->with($this->equalTo('foo'))->will($this->returnValue(false));
        
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($context);
        $this->assertSame(false, $translate->context('foo'));
    }
    
    public function testShouldBeAbleToTranslateContextualInput()
    {
        $context = $this->getContextMock();
        $context->expects($this->any())->method('appliesTo')->with($this->equalTo('foo'))->will($this->returnValue(true));
        $context->expects($this->once())->method('translate')->with($this->equalTo('bar'))->will($this->returnValue('foobar'));
        
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($context);
        
        $this->assertEquals(array('foo' => 'foobar'), $translate->translate(array('foo' => 'bar')));
    }
    
    public function testShouldBeAbleToTranslateNestedContextualInput()
    {
        $context = $this->getContextMock();
        $context->expects($this->any())->method('appliesTo')->with($this->equalTo('foo'))->will($this->returnValue(true));
        $context->expects($this->once())->method('translate')->with($this->equalTo('bar'))->will($this->returnValue('foobar'));
        
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($context);
        
        $this->assertEquals(array('bar' => array('foo' => 'foobar')), $translate->translate(array('bar' => array('foo' => 'bar'))));
    }
    
    public function testShouldNotTranslateNull()
    {
        $context = $this->getContextMock();
        $context->expects($this->never())->method('translate');
        
        $translate = new Xi_Translate_Contextual();
        $translate->addContext($context);
        
        $this->assertEquals(array('foo' => null), $translate->translate(array('foo' => null)));
    }
}
