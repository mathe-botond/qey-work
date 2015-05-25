<?php
namespace qeywork;

class ClientEntityDispatcher {
    protected $forms = array();
    
    public function addForm(FormData $form) {
        $this->forms[] = $form;
    }
    
    public function output() {
        $code = 'var entitys = entitys || {}; ';
        foreach ($this->forms as $form)
        {
            $class = get_class($form);
            $class = str_replace('\\', '', $class);
            $clientEntityDecriptor = $form->toClientEntity();
            $code .= 'entitys.' . $class . ' = ' . json_encode($clientEntityDecriptor) . ";\n\n";
        }
        return $code;
    }
}
