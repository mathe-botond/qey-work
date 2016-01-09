<?php
namespace qeywork;

/**
 * @author Dexx
 */
class PostFormData extends FormData {
    protected $prg;
    
    public function __construct(
            Entity $entity,
            PostRedirectGetUrls $prg = null, 
            $submitLabel = null) {
        
        $this->prg = $prg;
        parent::__construct($entity, $submitLabel);
    }
    
    public function setPrg(PostRedirectGetUrls $prg) {
        $this->prg = $prg;
    }
    
    public function transferFields(FormData $form) {
        $this->fieldSet = $form->getFieldSet();
    }
    
    /**
     * @return PostRedirectGetUrls
     */
    public function getPrg() {
        if ($this->prg == null) {
            throw new NullRefenceException('$this->prg property is null');
        }
        return $this->prg;
    }
}
