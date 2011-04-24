<?php
/**
 * @category    Xi
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_LinkTo extends Xi_View_Helper_Element
{
    public function linkTo($url, $content, array $attributes = array())
    {
        return $this->element('a',
                              $content,
                              array('href' => $url) + $attributes);
    }
}


