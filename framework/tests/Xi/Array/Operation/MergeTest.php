<?php
/**
 * @category    Xi_Test
 * @package     Xi_Array
 * @group       Xi_Array
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Array_Operation_MergeTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeCreatedWithStaticMethod()
    {
        $this->assertEquals(new Xi_Array_Operation_Merge(array('one' => 'foo'), array('two' => 'bar'), array('option' => 'value')),
                            Xi_Array_Operation_Merge::create(array('one' => 'foo'), array('two' => 'bar'), array('option' => 'value')));
    }

    public function testCanMergeNonIntersectingIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('one' => 'foo'), array('two' => 'bar'));
        $this->assertEquals(array('one' => 'foo', 'two' => 'bar'), $merge->execute());
    }

    public function testCanMergeIntersectingIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('value' => 'primary'), array('value' => 'secondary'));
        $this->assertEquals(array('value' => 'secondary'), $merge->execute());
    }

    public function testAppendsNumericalIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('one', 'two'), array('three', 'four'));
        $this->assertEquals(array('one', 'two', 'three', 'four'), $merge->execute());
    }

    public function testCanMergeNestedNonIntersectingIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('value' => array('one' => 'foo')),
                                    array('value' => array('two' => 'bar')));
        $this->assertEquals(array('value' => array('one' => 'foo', 'two' => 'bar')), $merge->execute());
    }

    public function testAppendsNestedNumericalIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('value' => array('one', 'two')),
                                    array('value' => array('three', 'four')));
        $this->assertEquals(array('value' => array('one', 'two', 'three', 'four')), $merge->execute());
    }

    public function testCanMergeFlatDataWithMixedIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('foo' => 'bar', 'one'), array('two'));
        $this->assertEquals(array('foo' => 'bar', 'one', 'two'), $merge->execute());
    }

    public function testCanMergeNestedDataWithMixedIndexes()
    {
        $merge = new Xi_Array_Operation_Merge(array('value' => array('foo' => 'bar', 'one')),
                                    array('value' => array('two')));
        $this->assertEquals(array('value' => array('foo' => 'bar', 'one', 'two')), $merge->execute());
    }

    public function testOverrideModeCanBeSetInConstructor()
    {
        $merge = new Xi_Array_Operation_Merge(array('one', 'two'), array('three', 'four'), array('mode' => 'override'));
        $this->assertEquals(array('three', 'four'), $merge->execute());
    }
    
    public function testOverrideModeCanBeSetInExecute()
    {
        $merge = new Xi_Array_Operation_Merge(array('one', 'two'), array('three', 'four'));
        $this->assertEquals(array('three', 'four'), $merge->execute(array('mode' => 'override')));
    }

    public function testOverrideModeCascadesToChildren()
    {
        $merge = new Xi_Array_Operation_Merge(array('value' => array('one', 'two')),
                                    array('value' => array('three', 'four')),
                                    array('mode' => 'override'));
        $this->assertEquals(array('value' => array('three', 'four')), $merge->execute());

        $merge = new Xi_Array_Operation_Merge(array('value' => array('one', 'two')),
                                    array('value' => array('three', 'four')));
        $this->assertEquals(array('value' => array('three', 'four')), $merge->execute(array('mode' => 'override')));
    }
}
