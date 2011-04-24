<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Paginator_Range_Sliding extends Xi_Paginator_Range_Abstract
{
    /**
     * @return array
     */
    public function _calculateBounds()
    {
        $firstPage = $this->getFirstPage();
        $lastPage  = $this->getLastPage();
        $pageRange = $this->getPageRange();
        
        $upperBound = $this->getCurrentPage() + ceil($pageRange / 2);
        if ($upperBound > $lastPage) {
            $upperBound = $lastPage;
        }
        
        $lowerBound = $upperBound - $pageRange;
        if ($lowerBound < $firstPage) {
            $lowerBound = $firstPage;
        }
        return array($lowerBound, $upperBound);
    }

}
