<?php
namespace QeyWork\View\Forms\Visuals;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlObjectList;
use QeyWork\View\Html\HtmlWrapperNode;
use QeyWork\View\Html\IHtmlObject;
use QeyWork\View\Html\NullHtml;

/**
 * TODO: make it up to date also implement IFormVisual 
 */
class FormVisualUsingList extends FormVisualBasis {
    private $h;

    public function __construct(HtmlBuilder $h) {
        $this->h = $h;
        parent::__construct($h);
    }

    public function base(
            $formAttriubtes,
            IHtmlObject $hiddenInputs,
            HtmlObjectList $rows,
            IHtmlObject $submit) {

        return $this->h->form()->cls('form-list-visual')->attr($formAttriubtes)->content(
            $hiddenInputs,
            $this->h->ul()->cls('form')->content(
                $rows,
                $this->h->li()->cls('submit-button-container')->content(
                    $submit
                )
            )
        );
    }
    
    public function entry($id, $class, $label, IHtmlObject $input, $comment, $message) {

        return $this->h->li()->id($id)->cls($class)->content(
            $this->h->label()->cls('form-entry-label')->text($label),
            $input, new HtmlWrapperNode($message),
            $this->h->span()->cls('form-entry-comment')->text($comment)
        );
    }

    public function fieldSet(HtmlObjectList $children, $title = null, $class = null) {
        $titleNode = new NullHtml();
        if ($title != null) {
            $titleNode = $this->h->legend()->text($title);
        }
        return $this->h->fieldset()->cls($class)->content(
            $titleNode, $children
        );
    }
}
