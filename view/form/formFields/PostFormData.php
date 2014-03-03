<?php
namespace qeywork;

/**
 * @author Dexx
 */
class PostFormData extends FormData {
    protected $prg;
    
    public function __construct(Model $model, PostRedirectGetUrls $prg, $submitLabel) {
        $this->prg = $prg;
        parent::__construct($model, $submitLabel);
    }
    
    /**
     * @return PostRedirectGetUrls
     */
    public function getPrg() {
        return $this->prg;
    }
}

?>
