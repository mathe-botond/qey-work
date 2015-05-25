<?php
namespace qeywork;

/**
 * Main tile of the webpage. It's content is loaded depending on the URL, using the classes and parameters required.
 * @author Dexx
 */
class Content extends Page {

    /** @var IPage */
    protected $content;
    
    public function __construct(IPage $content) {
        $this->content = $content;
    }
    
    public function ViewDI(
            Resources $resources,
            QeyMeta $meta,
            User $user,
            array $args) {
        $this->content->ViewDI($resources, $meta, $user, $args);
    }

    public function getTitle() {
        return $this->content->getTitle();
    }
    
    public function render(HtmlBuilder $h) {
        return $this->content->render($h);
    }
}
