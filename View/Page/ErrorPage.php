<?php
namespace QeyWork\View\Page;

use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\NullHtml;

class ErrorPage extends Page {
    protected $code;
    protected $title;
    protected $message;

    public $errorCodes = array(
        '400' => array(
            'title' => 'Bad Request',
            'description' => 'The request cannot be fulfilled due to bad syntax.'
        ),
        '403' => array(
            'title' => 'Forbidden',
            'description' => 'You do not have permission to access this resource.'
        ),
        '404' => array(
            'title' => 'Not Found',
            'description' => 'The requested resource could not be found.'
        ),
        '405' => array(
            'title' => 'Method Not Allowed',
            'description' => 'Expected another method, that the one was provided'
        ),
        '500' => array(
            'title' => 'Internal Server Error',
            'description' => 'Something went wrong in the rendering of this page'
        ),
        '501' => array(
            'title' => 'Not Implemented',
            'description' => 'Functionality not (yet) implemented'
        ),
        '503' => array(
            'title' => 'Service Unavailable',
            'description' => 'The server or service is currently unavailable'
        )
    );
    /**
     * @var \Exception
     */
    private $e;

    public function __construct($code = 500, \Exception $e = null) {
        if (! array_key_exists($code, $this->errorCodes) ) {
            return ;
        }

        $this->code = $code;
        $this->message = $this->errorCodes[$this->code]['description'];
        $this->title = $this->errorCodes[$this->code]['title'];
        $this->e = $e;
    }

    public function getTitle() {
        return $this->code . ' - ' . $this->title;
    }

    public function render(HtmlBuilder $h) {

        $e = ($this->e != null) ?
            $h->div()->cls('details')->content(
                $h->h5()->text(get_class($this->e) . " - " . $this->e->getMessage()),
                $h->p()->text($this->e->getFile() . $this->e->getLine()))
            : new NullHtml();

        return $h->article()->content(
            $h->h2()->text($this->code),
            $h->p()->text($this->message),
            $e
        );
    }
}