<?php
namespace qeywork;

interface IFormVisual extends IVisual {    
    /**
     * The skeleton of the form
     * @param array $formAttributes: action, method, enctype, etc. attributes
     * @param HtmlEntityList $hiddenInputs: hidden inputs like form id
     * @param HtmlEntityList $rows: a form row, which is a result of $this->row(...)
     * @param HtmlEntity $submit: submit and other buttons for the form,
     *          output of $this->submit
     */
    public function base(
            $formAttriubtes,
            IHtmlEntity $hiddenInputs,
            HtmlEntityList $rows,
            IHtmlEntity $submit);
    
    /**
     * A form entry
     * @param string $id of the entry
     * @param string $class of row (e.g. "error" on error, but mostly empty)
     * @param string $label of the entry
     * @param HtmlEntity $input of the entry
     * @param string $comment explanation for this form entry
     * @param string $message about the entry (e.g. error message)
     */
    public function entry($id, $class, $label, IHtmlEntity $input, $comment, $messages);
    
    /**
     * @param string $class
     * @param string $message 
     */
    public function message($id, $class, $message);
    
    public function hiddenSubmitData($name, $value);
}
