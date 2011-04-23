<?php
/**
 * @package		Xi Framework
 * @author		Ezku (dmnEe0 at gmail dot com)
 */
class IndexController extends Xi_Controller_Action
{
    public function indexAction()
    {
        return $this->getModel()->getStatus();
    }
}

