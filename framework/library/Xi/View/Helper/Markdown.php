<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_Markdown
{
    protected $_parser;
    
    public function __construct()
    {
        $this->_parser = new Markdown_Parser;
    }
    
    public function markdown($text)
    {
        return $this->_parser->transform($text);
    }
}

