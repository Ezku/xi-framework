<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_View_Factory extends Xi_Factory
{
    public function init()
    {
        $this->actAs('cached');
    }

    public function create($moduleName = null)
    {
        $view = new Zend_View;
        $view->addHelperPath('Xi/View/Helper', 'Xi_View_Helper_');
        $view->addFilterPath('Xi/View/Filter', 'Xi_View_Filter_');
        return $view;
    }
}
