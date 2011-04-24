<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_View_Helper_Url extends Zend_View_Helper_Url
{
    /**
     * Generates an url given the name of a route. Modifies the default URL
     * generation behaviour so that only module, controller and action will default
     * to the current values unless $name is specified or $reset is set
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        if (($name === null) && !$reset) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $urlOptions += array(
                'module' => $request->getModuleName(),
                'controller' => $request->getControllerName(),
                'action' => $request->getActionName(),
            );
        }
        return parent::url($urlOptions, $name, true, $encode);
    }
}

