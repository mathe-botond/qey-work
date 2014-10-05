<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyWork {
    const DEFAULT_HOME_PAGE = 'home';

    /** @var Locations */
    private $locations;
    /** @var PageHandler */
    private $pageHandler;
    /** @var Autoloader */
    private $autoloader;
    /** @var QeyWorkAssambler  */
    protected $assambler;
    /** @var Globals  */
    private $globals;
    
    protected $pages;
    protected $actions;
    
    private $layout;

    public function __construct(Locations $locations,
            $indexPageClass,
            $buildLater = false) {
        
        global $qeyWorkAutoloader;
        $this->autoloader = $qeyWorkAutoloader;
        $this->locations = $locations;
        
        $this->assambler = new QeyWorkAssambler();
        $this->globals = new Globals();
        
        $this->pages = new PageRouteCollection($indexPageClass);
        $this->actions = new ActionRouteCollection();
        
        if (! $buildLater) {
            $this->build();
        }
    }
    
    public function configureDb(DBConfig $config) {
        $this->assambler->configureDb($config);
    }
    
    public function setAssambler(QeyWorkAssambler $assambler) {
        $this->assambler = $assambler;
    }
    
    /**
     * @return QeyWorkAssambler
     */
    public function getAssambler() {
        return $this->assambler;
    }
    
    public function setGlobals(Globals $globals) {
        $this->globals = $globals;
    }
    
    public function getGlobals() {
        return $this->globals;
    }
    
    public function build() {
        $this->assambler->setupIoC($this->locations, $this->globals);
    }
    
    public function setLayout($layoutClass) {
        $this->layout = $this->assambler->createLayout($layoutClass);
        if (! $this->layout instanceof ILayout) {
            throw new TypeException($this->layoutClass, 'ILayout');
        }
    }
    
    public function registerPageClass($token, $pageClass) {
        $this->pages->addPageClass($token, $pageClass);
    }
    
    public function registerActionClass($token, $actionClass) {
        $this->actions->addActionClass($token, $actionClass);
    }
    
    public function getLayout() {
        if ($this->layout == null) {
            $this->layout = $this->assambler->createLayout();
        }
        return $this->layout;
    }

    /**
     * Creates a page based on the URL
     * @param string $defaultPage the page used if no URL parameter is defined
     * @return type
     */
    public function render() {
        $this->assambler->setupIocForPageCreation($this->pages);
        $pageHandler = $this->assambler->createPageHandler();
        $pageClass = $pageHandler->getRequestedPage($this->pages);
        if (is_string($pageClass)) {
            $page = $this->assambler->createPage($pageClass);
        }
        $pageHandler->postProcess($page);
        
        $layout = $this->getLayout();        
        $layout->setContent($page);
        return $layout->render();
    }
    
    public function run() {
        /* @var $actionHandler ActionsHandler */
        $actionHandler = $this->assambler->createActionHandler();
        $actionClass = $actionHandler->getRequestedAction($this->actions);
        $action = $this->assambler->createAction($actionClass);
        return $action->execute();
    }
}
