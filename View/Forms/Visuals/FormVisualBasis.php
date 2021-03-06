<?php
namespace QeyWork\View\Forms\Visuals;
use QeyWork\View\Html\HtmlBuilder;

/**
 * Description of FormVisualBasis
 *
 * @author Dexx
 */
abstract class FormVisualBasis implements IFormVisual {

    /** @var HtmlBuilder */
    private $h;
    
    protected $submitLabel;
    
    public function __construct(HtmlBuilder $h, $submitLabel = null) {
        $this->submitLabel = $submitLabel;
        $this->h = $h;
    }
    
    public function message($id, $class, $messages) {
        $messageContainer = $this->h->ul()->id($id)->cls($class);
        foreach ($messages as $key => $message) {
            $messageContainer->append($this->h->li()->cls($key)->text($message));
        }
        return $messageContainer;
    }
    
    public  function submit() {
        $submit = ($this->submitLabel == '') ? 'Submit' : $this->submitLabel;
        return $this->h->tr()->cls('submit')->content(
            $this->h->td()->attr('colspan', '2')->content(
                $this->h->input()->type('submit')->cls('button')->value($submit)
            )
        );
    }
    
    public function hiddenSubmitData($name, $value) {
        return $this->h->input()->type('hidden')->name($name)->value($value);
    }
}
