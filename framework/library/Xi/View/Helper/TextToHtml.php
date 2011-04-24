<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_TextToHtml
{
    /**
     * Simple text-to-html conversion
     *
     * @param string $text
     * @return string
     */
    public function textToHtml($text)
    {
        $text = preg_replace("#(\r\n|\n){2,}#m", "</p><p>", $text);
        $text = nl2br($text);
        return '<p>' . $text . '</p>'; 
    }
}
