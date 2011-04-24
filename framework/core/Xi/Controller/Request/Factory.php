<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Request_Factory extends Xi_Factory
{
    public function init()
    {
        $this->actAs('singleton');
    }

    public function create()
    {
        $class   = $this->getOption('class', 'Xi_Controller_Request');
        $request = new $class;
        $params  = $this->_locator->config->params;
        if (isset($params->baseUrl)) {
            $request->setBaseUrl($params->baseUrl);
        }
        return $request;
    }
}
