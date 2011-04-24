<?php
/**
 * @category    Xi_Test
 * @package     Xi_State
 * @group       Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_State_Machine_RPNTest extends PHPUnit_Framework_TestCase
{
    public function getCalculator()
    {
        $fsm = new Xi_State_Machine(array('init', 'build'));

        $fsm->record()->from('init')->on(new Zend_Validate_Int)->to('build');
        $fsm->record()->from('build')->on(new Zend_Validate_Int)->to('build');
        $fsm->record()->from('build')->on(' ')->to('init');
        $fsm->record()->from('init')->on(' ')->to('init');
        $fsm->record()->from('init')->on('=')->to('init');
        $fsm->record()->from('init')->on(array('+', '-', '/', '*', '^'))->to('init');

        $fsm->record()->when()->from('init')->to('build')->trigger(array($this, 'beginBuild'));
        $fsm->record()->when()->from('build')->to('build')->trigger(array($this, 'continueBuild'));
        $fsm->record()->when()->from('build')->to('init')->trigger(array($this, 'endBuild'));

        $fsm->record()->in('init')->on('=')->trigger(array($this, 'doEqual'));
        $fsm->record()->in('init')->on(array('+', '-', '/', '*', '^'))->trigger(array($this, 'doOperation'));

        return $fsm;
    }

    public function beginBuild($fsm)
    {
        $fsm->getStack()->push($fsm->getInput());
    }

    public function continueBuild($fsm)
    {
        $stack = $fsm->getStack();
        $s = $stack->pop();
        $stack->push($s . $fsm->getInput());
    }

    public function endBuild($fsm)
    {
        $fsm->getStack()->push((int) $fsm->getStack()->pop());
    }

    public function doOperation($fsm)
    {
        $stack = $fsm->getStack();
        $right = $stack->pop();
        $left = $stack->pop();

        $result = null;
        switch ($fsm->getInput()) {
            case '+':
                $result = $left + $right;
            break;
            case '-':
                $result = $left - $right;
            break;
            case '*':
                $result = $left * $right;
            break;
            case '/':
                $result = $left / $right;
            break;
            case '^':
                $result = pow($left, $right);
            break;
        }

        $stack->push($result);
    }

    public function doEqual($fsm)
    {
        $fsm->getOutput()->push($fsm->getStack()->peek());
    }

    public function testSupportsSimpleOperationsWithSingleDigitNumbers()
    {
        $calculator = $this->getCalculator();
        foreach (str_split('1 1 + =') as $token) {
            $calculator->process($token);
        }
        $this->assertEquals(2, $calculator->getOutput()->peek());

        $calculator = $this->getCalculator();
        foreach (str_split('1 1 - =') as $token) {
            $calculator->process($token);
        }
        $this->assertEquals(0, $calculator->getOutput()->peek());
    }

    public function testSupportsManyDigitNumbers()
    {
        $calculator = $this->getCalculator();
        foreach (str_split('14 16 + =') as $token) {
            $calculator->process($token);
        }
        $this->assertEquals(30, $calculator->getOutput()->peek());
    }

    public function testSupportsManySuccessiveOperations()
    {
        $calculator = $this->getCalculator();
        foreach (str_split('1 2 3 + + =') as $token) {
            $calculator->process($token);
        }
        $this->assertEquals(6, $calculator->getOutput()->peek());

        $calculator = $this->getCalculator();
        foreach (str_split('2 3 4 * * =') as $token) {
            $calculator->process($token);
        }
        $this->assertEquals(24, $calculator->getOutput()->peek());
    }
}
