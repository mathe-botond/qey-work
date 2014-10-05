<?php
namespace qeywork;

class FormVisualUsingTable extends  FormVisualBasis{
        
    public function base(
            $formAttriubtes,
            IHtmlEntity $hiddenInputs,
            HtmlEntityList $rows,
            IHtmlEntity $submit) {
        $h = new HtmlFactory();
        $form = $h->form()->attr($formAttriubtes)->content(
            $hiddenInputs,
            $h->table()->cls('form')->content(
                $rows,
                $h->tr()->content(
                    $h->td()->colspan(2)->cls('submit-row')->content(
                        $submit
                    )
                )
            )
        );
        return $form;
    }
    
   public function entry($id, $class, $label, IHtmlEntity $input, $comment, $message) {
       $h = new HtmlFactory();
       return $h->tr()->id($id)->cls($class)->content(
           $h->td()->cls('label form-entry-label')->text($label),
           $h->td()->cls('input form-entry-input')->content(
               $input, new TextNode($comment), $message
           )
       );
    }
}
