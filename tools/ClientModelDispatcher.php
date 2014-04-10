<?php
namespace qeywork;

class ClientModelDispatcher {
    protected $forms = array();
    
    public function addForm(FormData $form) {
        $this->forms[] = $form;
    }
    
    public function output() {
        $code = 'var models = models || {}; ';
        foreach ($this->forms as $form)
        {
            $class = get_class($form);
            $class = str_replace('\\', '', $class);
            $clientModelDecriptor = $form->toClientModel();
            $code .= 'models.' . $class . ' = ' . json_encode($clientModelDecriptor) . ";\n\n";
        }
        return $code;
    }
}
