<?php
namespace qeywork;

/**
 * TODO: make it up to date also implement IFormVisual 
 */
class FormVisualUsingList extends FormVisualBasis {
    public function base(
            $formAttriubtes,
            IHtmlEntity $hiddenInputs,
            HtmlEntityList $rows,
            IHtmlEntity $submit) {
        $h = new HtmlFactory();
        return $h->form()->cls('form-list-visual')->attr($formAttriubtes)->content(
            $hiddenInputs,
            $h->ul()->cls('form')->content(
                $rows,
                $submit
            )
        );
    }
    
    public function entry($id, $class, $label, IHtmlEntity $input, $comment, $message) {
        $h = new HtmlFactory();
        return $h->li()->id($id)->cls($class)->content(
            $h->label()->cls('form-entry-label')->text($label),
            $input, $message,
            $h->span()->cls('form-entry-comment')->text($comment)
        );
    }
}
?>