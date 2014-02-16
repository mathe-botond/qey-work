<?php
namespace qeywork;

/**
 * A class helping you create forms. Initialises client code
 */
class PostForm extends Form implements IRenderable {
    /** @var IFormVisual $visual */
    protected $visual;
    protected $errors;
    /** @var FormCollection */
    protected $container;
    
    protected $id;
    
    public function setMultipart(bool $multipart) {
        $this->multiPart = $multipart;
    }
    
    /**
     * Constructor of this class
     */
    public function __construct(
            Locations $locations,
            FormCollection $container,
            PostFormData $formData,
            QeyMeta $meta,
            IFormVisual $visual = null) {
        
        
        parent::__construct($formData, $formData->getPrg()->getAction(), $visual);
        
        $this->formData = $formData;
        $this->visual = $visual;
        $this->errors = array();
        
        $this->container = $container;
        $this->id = $this->container->add($this->formData);
        
        $this->visual = ($visual === null)
                ? new FormVisualUsingTable()
                : $visual;

        $js = $meta->getJsLinkCollection();
        $jsDir = $js->getQeyWorkJs();
        $js->add(
            $jsDir->file("jquery.form.js"),
            $jsDir->file("jquery.validator.js"),
            $jsDir->file("qey.form.js"),
            $locations->getUrlOfAction(
                    ModelDispacherForExternalCall::NAME
                )->field('form-id', $this->id)
        );
        
        $this->method = 'POST';
    }
    
    public function createHiddenData() {
        return $this->formVisual->hiddenSubmitData('qey-form-id', $this->id);
    }
    
    /**
     * @return PostRedirectGetUrls
     */
    public function getPrg() {
        return $this->formData->getPrg();
    }
}
?>
