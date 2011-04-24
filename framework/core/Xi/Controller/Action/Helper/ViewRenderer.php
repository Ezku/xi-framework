<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Action_Helper_ViewRenderer extends Zend_Controller_Action_Helper_ViewRenderer
{
    /**
     * Default prefix for helper and filter classes
     *
     * @var string
     * @see _generateDefaultPrefix()
     */
    protected $_defaultPrefix = ':appName_View';

    public function init()
    {
        if ($this->getFrontController()->getParam('noViewRenderer')) {
            return;
        }

        $controller = $this->getActionController();

        $view = $controller->getView()->getEngine();
        if ($view) {
            $this->setView($view);
        }

        // TODO
        //$inflector = clone $controller->getPaths()->view->getInflector();
        //$this->setInflector($inflector);
        //$inflector->setTargetReference($this->_inflectorTarget);

        $this->initView();
    }

    /**
     * Generate a class prefix for helper and filter classes
     *
     * @return string
     */
    protected function _generateDefaultPrefix()
    {
        $class = get_class($this->_actionController);
        $pos   = strpos($class, '_');
        if ((null === $this->_actionController) || (false === $pos)) {
            $prefix = $this->_actionController->getPaths()->inflect($this->_defaultPrefix);
        } else {
            $prefix = substr($class, 0, $pos) . '_View';
        }

        return $prefix;
    }
}

