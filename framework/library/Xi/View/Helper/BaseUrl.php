<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_BaseUrl
{
    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
    }
    
    /**
     * Retrieve base url, optionally appended with a path
     * 
     * @param null|string
     * @return string
     */
    public function baseUrl($path = null)
    {
        $base = $this->getBaseUrl();
        if ($path === null) {
            return $base;
        }
        return $this->concatenatePaths($base, $path);
    }
    
    /**
     * @param $left path
     * @param $right path
     * @return string
     */
    public function concatenatePaths($left, $right)
    {
        return rtrim($left, '/\\') . '/' . ltrim($right, '/\\');
    }
}

