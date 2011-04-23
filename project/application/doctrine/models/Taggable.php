<?php
class Taggable extends Doctrine_Template
{
    /**
     * Array of Taggable options
     *
     * @var string
     */
    protected $_options = array(
        'alias'   => 'Tags',
        'foreign' => 'tag_id'
    );

    /**
     * __construct
     *
     * @param string $array 
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
        if (empty($this->_options['refClass'])) {
            throw new Exception('Missing jump table in Taggable options');
        }
    }
    
    public function setTableDefinition()
    {
        $this->hasMany(sprintf('Tag as %s', $this->_options['alias']), $this->_options);
    }
    
    public function getTags()
    {
        $tags = array();
        foreach ($this->getInvoker()->Tags as $tag) {
            $tags[] = $tag->name;
        }
        return $tags;
    }
}
