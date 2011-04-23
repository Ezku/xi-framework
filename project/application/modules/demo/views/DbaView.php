<?php
class Demo_DbaView extends Xi_Controller_View
{
    public function displayDoctrine()
    {
        $this->items = $this->getModel()->getDoctrineItems();
    }
    
    public function displayZend()
    {
        $this->items = $this->getModel()->getZendItems();
    }
}
