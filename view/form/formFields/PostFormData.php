<?php
namespace qeywork;

/**
 * @author Dexx
 */
class PostFormData extends FormData {
    protected $prg;
    
    public function __construct(Model $model, PostRedirectGetUrls $prg = null, $submitLabel = null) {
        $this->prg = $prg;
        parent::__construct($model, $submitLabel);
    }
    
    public function setPrg(PostRedirectGetUrls $prg) {
        $this->prg = $prg;
    }
    
    public function transferFields(FormData $form) {
        $this->fields = $form->getFields();
    }
    
    /**
     * @return PostRedirectGetUrls
     */
    public function getPrg() {
        if ($this->prg == null) {
            throw new q\NullRefenceException('$this->prg property is null');
        }
        return $this->prg;
    }
}
