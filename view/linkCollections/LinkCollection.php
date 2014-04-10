<?php
namespace qeywork;

abstract class LinkCollection implements ILinkCollection
{
    protected $files;

    public function __construct() {
        $this->files = array();
    }
    
    protected function addSingle(Url $file) {
        $this->files[] = $file;
    }
    
    /**
     * Add file to collection
     * @param Url $file,... File(s) to add to colelction
     */
    public function add()
    {
        for ($i = 0 ; $i < func_num_args(); $i++) {
            $this->addSingle(func_get_arg($i));
        }
    }
    
    protected abstract function createEntry($file);

    public function render() {
        $output = new HtmlEntityList();
        foreach ($this->files as $file) {
            $output[] = $this->createEntry($file);
        }
        return $output;
    }
}
