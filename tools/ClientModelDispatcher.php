<?php
namespace qeywork;

class ClientModelDispatcher {
    protected $models = array();
    
    public function addModel(Model $model) {
        $this->models[] = $model;
    }
    
    public function output() {
        $code = 'var models = models || {}; ';
        foreach ($this->models as $model)
        {
            /* @var $model Model */
            
            $class = get_class($model);
            $clientModelDecriptor = $model->toClientModel();
            $code .= 'models.' . $class . ' = ' . json_encode($clientModelDecriptor) . ";\n\n";
        }
        return $code;
    }
}
?>