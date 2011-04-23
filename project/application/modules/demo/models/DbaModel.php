<?php
class Demo_DbaModel extends Xi_Controller_Model
{
    public function getDoctrineItems()
    {
        $query = Doctrine_Query::create()
            ->select('u.*, g.*, p.*, pt.*, m.*, mt.*')
            ->from('User u')
            ->leftJoin('u.Groups g')
            ->leftJoin('u.Posts p')
            ->leftJoin('p.Tags pt')
            ->leftJoin('p.Media m')
            ->leftJoin('m.Tags mt');
        return $query->execute();
    }
    
    public function getZendItems()
    {
    }
}
