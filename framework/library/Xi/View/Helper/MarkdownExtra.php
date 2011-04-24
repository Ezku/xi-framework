<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_View_Helper_MarkdownExtra
{
    protected $_parser;
    
    public function __construct()
    {
        $this->_parser = new Markdown_Extra_Parser;
    }
    
    public function markdownExtra($text)
    {
        return $this->_parser->transform($text);
    }
}

