<?php
namespace qeywork;

/**
 * Description of PostFormFactory
 *
 * @author Dexx
 */
class PostFormFactory {
    protected $forms;
    private $resources;
    private $meta;
    
    public function __construct(
            Resources $resources,
            QeyMeta $meta) {
        
        $this->meta = $meta;
        $this->resources = $resources;
        
        $this->forms = new FormCollection($resources->getSession());
    }
    
    public function getFormCollection() {
        return $this->forms;
    }
    
    public function createForm(      
            PostFormData $formData,
            IFormVisual $visual = null) {
        
        if ($this->forms->wasFormSubmitted()) {
            try {
                $formData = $this->forms->getSubmittedForm();
                $this->forms->cleanSubmitted();
            } catch (ArgumentException $e) {
                $this->forms->setSubmittedFormId(null); //cleanup
            }
        }
        
        return new PostForm(
            $this->resources->getLocations(),
            $this->forms,
            $formData,
            $this->meta,
            $visual);;
    }
}
