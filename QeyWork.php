<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyWork {
    const DEFAULT_HOME_PAGE = 'home';

    /** @var Locations */
    private $locations;
    /** @var Autoloader */
    private $autoloader;
    /** @var QeyWorkAssambler  */
    protected $assembler;
    /** @var Globals  */
    private $globals;
    /** @var IContentPostProcessor  */
    protected $contentPostProcessor;
    
    protected $pages;
    protected $actions;
    
    private $layout;

    public function __construct(Locations $locations,
            $indexPageClass,
            $buildLater = false) {
        
        global $qeyWorkAutoloader;
        $this->autoloader = $qeyWorkAutoloader;
        $this->locations = $locations;
        
        $this->assembler = new QeyWorkAssambler();
        $this->globals = new Globals();
        
        $this->pages = new PageRouteCollection($indexPageClass);
        $this->actions = new ActionRouteCollection();
        
        if (! $buildLater) {
            $this->build();
        }
    }
    
    public function configureDb(DBConfig $config) {
        $this->assembler->configureDb($config);
    }
    
    public function setAssembler(QeyWorkAssambler $assembler) {
        $this->assembler = $assembler;
    }
    
    /**
     * @return QeyWorkAssambler
     */
    public function getAssembler() {
        return $this->assembler;
    }
    
    public function setGlobals(Globals $globals) {
        $this->globals = $globals;
    }
    
    public function getGlobals() {
        return $this->globals;
    }
    
    public function build() {
        $this->assembler->setupIoC($this->locations, $this->globals);
    }
    
    public function setLayout($layoutClass) {
        $this->layout = $this->assembler->createLayout($layoutClass);
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
            $this->layout = $this->assembler->createLayout();
        }
        return $this->layout;
    }
    
    public function registerPagePostprocessor($postProcessorClass) {
        $this->assembler->registerPagePostProcessor($postProcessorClass);
    }

    public function registerContentPostProcessor($postProcessorClass) {
        $this->contentPostProcessor = $this->assembler->getIoC()->create($postProcessorClass);
    }
    
    public function addPageRouter(IPageRouter $router) {
        $this->pages->addRouter($router);
    }
    
    public function addActionRouter(IActionRouter $router) {
        $this->actions->addRouter($router);
    }

    private function postProcess($renderedLayout)
    {
        if ($this->contentPostProcessor == null) {
            return $renderedLayout;
        }

        return $this->contentPostProcessor->process($renderedLayout);
    }

    /**
     * Creates a page based on the URL
     * @param string $defaultPage the page used if no URL parameter is defined
     * @return type
     */
    public function render() {
        $layout = $this->getLayout();
        try {
            $this->assembler->setupIocForPageCreation($this->pages);

            $pageHandler = $this->assembler->getPageHandler();
            $pageClass = $pageHandler->getRequestedPage($this->pages);
            if (is_string($pageClass)) {
                $pageClass = $this->assembler->createPage($pageClass);
            }
            $page = $pageHandler->postProcess($pageClass);

            $layout->setContent($page);
            $renderedLayout = $layout->render();

            $renderedLayout = $this->postProcess($renderedLayout);
        }
        catch (RouteException $e) {
            $layout->setContent(new ErrorPage(404));
            $renderedLayout = $layout->render();
        }
        catch (\Exception $e) {
            $layout->setContent(new ErrorPage(500, $e));
            $renderedLayout = $layout->render();
        }

        return $renderedLayout;
    }
    
    public function run() {
        $this->actions->addRouter(new QeyActionRouter());
        
        /* @var $actionHandler ActionsHandler */
        $actionHandler = $this->assembler->getActionHandler();
        
        $actionClass = $actionHandler->getRequestedAction($this->actions);
        $action = $this->assembler->createAction($actionClass);
        return $action->execute();
    }
}
