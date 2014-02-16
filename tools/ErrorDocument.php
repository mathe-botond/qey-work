<?php
namespace qeywork;

class ErrorDocument extends BasicPage {
    protected $id;
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
    
    public function __construct($id = 500) {
        if (! array_key_exists($id, $this->errorCodes) ) {
            //ErrorPages::getInstance()->display('404', 'yo-dawg-i-heard-you-like-errors');
            return ;
        }
        
        $params = getParams();
        $this->id = $id;
        $this->message = $params->exists('msg') 
                ? $params->msg
                : $this->errorCodes[$this->id]['description'];
    }
    
    public function template() {
?>
        <h2>{title}</h2>
        <p class="description">{description}</p>
<?php
    }
    
    public function render($html = '') {
        return processTemplates( array(
                'title' => $this->id . ' - ' . $this->errorCodes[$this->id]['title'],
                'description' => $this->message
            ), $html);
    }
}
?>
