<?php
class Xi_Translate_ContextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Zend_Translate_Adapter
     */
    public function getTranslatorAdapterMock()
    {
        return $this->getMock(
        	'Zend_Translate_Adapter',
            array('_loadTranslationData', 'toString', 'setLocale', 'translate'),
            array(array())
        );
    }
    
    public function testMatchesNoSubjectByDefault()
    {
        $context = new Xi_Translate_Context();
        $this->assertFalse($context->appliesTo('foo'));
    }
    
    public function testMatchesSubjectWhenProvided()
    {
        $context = new Xi_Translate_Context();
        $context->setSubject('foo');
        $this->assertTrue($context->appliesTo('foo'));
    }
    
    public function testHasNoTranslatorByDefault()
    {
        $context = new Xi_Translate_Context();
        $this->assertNull($context->getTranslator());
    }
    
    public function testTranslatorCanBeSet()
    {
        $translator = $this->getTranslatorAdapterMock();
        $context = new Xi_Translate_Context();
        $context->setTranslator($translator);
        $this->assertEquals($translator, $context->getTranslator());
    }
    
    public function testDelegatesTranslationToTranslator()
    {
        $translator = $this->getTranslatorAdapterMock();
        $translator->expects($this->once())->method('translate')
            ->with($this->equalTo('foo'))->will($this->returnValue('bar'));
        
        $context = new Xi_Translate_Context();
        $context->setTranslator($translator);
        $this->assertEquals('bar', $context->translate('foo'));
    }
}
