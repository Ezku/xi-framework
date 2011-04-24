<?php
require_once 'texy/texy.php';

/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_Texy
{
    protected $_parser;
    
    public function __construct()
    {
        $this->_parser = new Texy;
    }
    
    public function texy($text, $singleline = false)
    {
        return $this->_parser->process($text, $singleline);
    }
}

