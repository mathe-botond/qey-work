<?php
namespace qeywork;

class FormVisualUsingTable extends  FormVisualBasis{
        
    public function base(
            $formAttriubtes,
            IHtmlEntity $hiddenInputs,
            HtmlEntityList $rows,
            IHtmlEntity $submit) {
        $h = new HtmlFactory();
        return $h->form()->attr($formAttriubtes)->content(
            $hiddenInputs,
            $h->table()->cls('form')->content(
                $rows,
                $submit
            )
        );
    }
    
   public function entry($id, $class, $label, IHtmlEntity $input, $comment, $message) {
       $h = new HtmlFactory();
       return $h->tr()->id($id)->cls($class)->content(
           $h->td()->cls('form-entry-label')->text($label),
           $h->td()->cls('form-entry-input')->content(
               $input, new TextNode($comment), $message
           )
       );
    }
}
?>