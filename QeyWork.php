<?php
namespace QeyWork;
use QeyWork\Common\ActionsHandler;
use QeyWork\Common\ArgumentException;
use QeyWork\Common\Config;
use QeyWork\Common\Globals;
use QeyWork\Common\IErrorHandler;
use QeyWork\Common\IRenderer;
use QeyWork\Common\IRunner;
use QeyWork\Common\IWebsiteBuilder;
use QeyWork\Common\ReDice\Dice;
use QeyWork\Common\RouteException;
use QeyWork\Common\Routers\ActionRouteCollection;
use QeyWork\Common\Routers\IActionRouter;
use QeyWork\Common\Routers\IPageRouter;
use QeyWork\Common\Routers\PageRouteCollection;
use QeyWork\Common\Routers\QeyActionRouter;
use QeyWork\Common\TypeException;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\IContentPostProcessor;
use QeyWork\View\Page\ErrorPage;
use QeyWork\View\Page\ILayout;

/**
 * @author Dexx
 */
class QeyWork implements IRenderer, IRunner {
    const DEFAULT_HOME_PAGE = 'home';

    /** @var Config */
    private $config;
    /** @var QeyWorkAssembler  */
    protected $assembler;
    /** @var Globals  */
    private $globals;
    /** @var IContentPostProcessor  */
    protected $contentPostProcessor;
    
    protected $pages;
    protected $actions;
    
    private $layout;
    private $appName;

    /** @var IErrorHandler */
    private $errorHandler;

    public function __construct(Config $config, $buildLater = false) {
        $this->config = $config;
        
        $this->assembler = new QeyWorkAssembler();
        $this->globals = new Globals();

        $this->pages = new PageRouteCollection($config->getIndex());
        $this->actions = new ActionRouteCollection();
        
        if (! $buildLater) {
            $this->build();
        }
        $this->appName = $config;
    }

    public function setAssembler(QeyWorkAssembler $assembler) {
        $this->assembler = $assembler;
    }
    
    /**
     * @return QeyWorkAssembler
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
        $this->assembler->setupIoC($this->config, $this->globals);
    }
    
    public function setLayout($layoutClass) {
        $this->layout = $this->assembler->createLayout($layoutClass);
        if (! $this->layout instanceof ILayout) {
            throw new TypeException($layoutClass, 'ILayout');
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
     * @return type
     * @internal param string $defaultPage the page used if no URL parameter is defined
     */
    public function render() {
        $h = new HtmlBuilder();
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
            $renderedLayout = $layout->render($h);

            $renderedLayout = $this->postProcess($renderedLayout);
        }
        catch (RouteException $e) {
            $layout->setContent(new ErrorPage(404));
            $renderedLayout = $layout->render($h);
        }
        catch (\Exception $e) {
            $layout->setContent(new ErrorPage(500, $e));
            $renderedLayout = $layout->render($h);
        }

        return $renderedLayout;
    }
    
    public function run() {
        try {
            $this->actions->addRouter(new QeyActionRouter());

            /* @var $actionHandler ActionsHandler */
            $actionHandler = $this->assembler->getActionHandler();

            $actionClass = $actionHandler->getRequestedAction($this->actions);
            $action = $this->assembler->createAction($actionClass);
            return $action->execute();
        } catch (\Exception $e) {
            if ($this->errorHandler != null) {
                $this->errorHandler->handle($e);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param string $class
     * @return IWebsiteBuilder
     * @throws ArgumentException
     */
    public static function createWebsite($class) {
        $dice = new Dice();
        $website =  $dice->create($class);
        if (! $website instanceof IWebsiteBuilder) {
            throw new ArgumentException("$class must be an IWebsite");
        }
        return $website;
    }

    public function registerActionErrorHandler($handler) {
        if (is_string($handler)) {
            $handler = $this->assembler->getIoC()->create($handler);
        }
        $this->errorHandler = $handler;
    }
}
