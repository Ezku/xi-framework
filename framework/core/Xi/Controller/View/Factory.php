<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_View
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_View_Factory extends Xi_Controller_Component_Factory
{
    protected $_enableDefaultComponentClass = true;
    protected $_defaultComponentClass = 'Xi_Controller_View';
    protected $_componentName = 'View';

    public function getComponentPath($paths, $parameters)
    {
        return $paths->view->classPath($parameters);
    }
}
