<?php
namespace qeywork;

abstract class LinkCollection implements ILinkCollection
{
    protected $files;

    public function __construct() {
        $this->files = array();
    }
    
    protected function addSingle($file) {
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
    
    protected abstract function createEntry(HtmlBuilder $h, $file);

    public function render(HtmlBuilder $h) {
        $output = new HtmlObjectList();
        foreach ($this->files as $file) {
            $output[] = $this->createEntry($h, $file);
        }
        return $output;
    }
}
