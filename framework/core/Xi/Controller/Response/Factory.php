<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Response_Factory extends Xi_Factory
{
    public function init()
    {
        $this->actAs('singleton');
    }

    public function create()
    {
        $class   = $this->getOption('class', 'Xi_Controller_Response');
        $request = new $class;
        $params  = $this->_locator->config->params;
        if (isset($params->renderExceptions)) {
            $request->renderExceptions((boolean) $params->renderExceptions);
        }
        return $request;
    }
}
