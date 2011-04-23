<?php
interface Xi_Factory_Behaviour_Interface extends Xi_Factory_Interface, Xi_Locator_Injectable_Interface
{
    public function setFactory(Xi_Factory_Interface $factory);
}
