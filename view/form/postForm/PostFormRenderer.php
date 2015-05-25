<?php
namespace qeywork;

/**
 * A class helping you create forms. Initialises client code
 */
class PostFormRenderer extends FormRenderer implements IRenderable {
    /** @var IFormVisual $visual */
    protected $visual;
    protected $errors;
    /** @var PostFormCollection */
    protected $formCollection;
    /** @var PostFormLinker */
    private $linker;
    
    protected $id;
    
    public function setMultipart(bool $multipart) {
        $this->multiPart = $multipart;
    }

    /**
     * Constructor of this class
     * @param PostFormCollection $formCollection
     * @param PostFormLinker $linker
     * @param IFormVisual $visual
     */
    public function __construct(
            PostFormCollection $formCollection,
            PostFormLinker $linker,
            IFormVisual $visual = null) {
        
        parent::__construct($visual);
        
        $this->visual = $visual;
        $this->errors = array();
        
        $this->formCollection = $formCollection;
        
        $this->visual = ($visual === null)
                ? new FormVisualUsingList()
                : $visual;

        $this->method = 'POST';
        $this->linker = $linker;
    }
    
    public function fillPostFormData(PostFormData $formData) {
        $this->setAction($formData->getPrg()->getAction());
        $this->fillFormData($formData);
        $this->formData = $this->formCollection->retrieveUserInput($formData);
        $this->id = $this->formCollection->add($this->formData);
    }
    
    public function createHiddenData() {
        $hiddenData = parent::createHiddenData();
        $hiddenData->append($this->formVisual->hiddenSubmitData('qey-form-id', $this->id));
        return $hiddenData;
    }
    
    /**
     * @return PostRedirectGetUrls
     */
    public function getPrg() {
        return $this->formData->getPrg();
    }
    
    public function render(HtmlBuilder $h) {
        $this->linker->link($this->id);
        return parent::render($h);
    }
}
