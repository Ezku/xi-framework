<?php
Xi_Loader::loadClass('Xi_Controller_Component_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Model
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Model_Factory extends Xi_Controller_Component_Factory
{
    protected $_enableDefaultComponentClass = true;
    protected $_defaultComponentClass = 'Xi_Controller_Model';
    protected $_componentName = 'Model';

    public function getComponentPath($paths, $parameters)
    {
        return $paths->model->classPath($parameters);
    }
}
