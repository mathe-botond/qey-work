<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 3/6/2015
 * Time: 8:56 PM
 */

namespace qeywork;

class ErrorPage extends Page {
    protected $id;
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
            'description' => 'Generic error message, no more specific information is suitable'
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

    public function __construct($id = 500, \Exception $e = null) {
        if (! array_key_exists($id, $this->errorCodes) ) {
            return ;
        }

        $this->id = $id;
        $this->message = $this->errorCodes[$this->id]['description'];
        $this->title = $this->errorCodes[$this->id]['title'];
        $this->e = $e;
    }

    public function getTitle() {
        return $this->title;
    }

    public function render() {
        $h = new HtmlFactory();
        $e = ($this->e != null) ?
            $h->div()->cls('details')->content(
                $h->h5()->text($this->e->getMessage()),
                $h->p()->text($this->e->getFile() . $this->e->getLine()))
            : new NullHtml();

        return $h->article()->content(
            $h->h2()->text($this->id),
            $h->p()->text($this->message),
            $e
        );
    }
}