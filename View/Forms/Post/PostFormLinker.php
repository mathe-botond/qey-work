<?php
namespace QeyWork\View\Forms\Post;
use QeyWork\Common\Addresses\Locations;
use QeyWork\View\Block\QeyMeta;
use QeyWork\View\Forms\EntityDispacherForExternalCall;

/**
 * Adds CSS and JS files needed by a PostForm
 * @author Dexx
 */
class PostFormLinker {

    /**
     * @var Locations
     */
    private $locations;

    /**
     * @var QeyMeta
     */
    private $meta;

    public function __construct(QeyMeta $meta, Locations $locations) {
        $this->meta = $meta;
        $this->locations = $locations;
    }
    
    public function link($formId) {
        $js = $this->meta->getJsLinkCollection();
        $jsDir = $js->getQeyWorkJs();
        $js->add(
            $jsDir->file("jquery.form.js"),
            $jsDir->file("jquery.validator.js"),
            $jsDir->file("qey.form.js"),
            $this->locations->getUrlOfAction(
                    EntityDispacherForExternalCall::NAME
                )->field('form-id', $formId)
        );
    }
}
