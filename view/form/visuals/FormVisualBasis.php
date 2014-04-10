<?php
namespace qeywork;

/**
 * Description of FormVisualBasis
 *
 * @author Dexx
 */
abstract class FormVisualBasis implements IFormVisual {
    protected $submitLabel;
    
    public function __construct($submitLabel = null) {
        $this->submitLabel = $submitLabel;
    }
    
    public function message($id, $class, $messages) {
        $h = new HtmlFactory();
        $messageContainer = $h->ul()->id($id)->cls($class);
        foreach ($messages as $key => $message) {
            $messageContainer->append($h->li()->cls($key)->text($message));
        }
        return $messageContainer;
    }
    
    public  function submit() {
        $submit = ($this->submitLabel == '') ? 'Submit' : $this->submitLabel;
        $h = new HtmlFactory();
        return $h->tr()->cls('submit')->content(
            $h->td()->attr('colspan', '2')->content(
                 $h->input()->type('submit')->cls('button')->value($submit)
            )
        );
    }
    
    public function hiddenSubmitData($name, $value) {
        $h = new HtmlFactory();
        return $h->input()->type('hidden')->name($name)->value($value);
    }
}
