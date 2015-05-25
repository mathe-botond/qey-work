<?php
namespace qeywork;

class FormVisualUsingTable extends  FormVisualBasis{
        
    public function base(
            $formAttriubtes,
            IHtmlObject $hiddenInputs,
            HtmlObjectList $rows,
            IHtmlObject $submit) {

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
    
   public function entry($id, $class, $label, IHtmlObject $input, $comment, $message) {

       return $h->tr()->id($id)->cls($class)->content(
           $h->td()->cls('label form-entry-label')->text($label),
           $h->td()->cls('input form-entry-input')->content(
               $input, new TextNode($comment), $message
           )
       );
    }
}
