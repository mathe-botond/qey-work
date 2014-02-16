<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class QeyWebsite implements IWebsite {
    /** @var Params */
    private $params;
    protected $engine;
    
    /**
     * @return IPageFactory
     */
    protected abstract function getApplicationPageFactory();
    
    /**
     * @return IActionFactory
     */
    protected abstract function getApplicationActionFactory();
        
    /**
     * @return ResourceCollection
     */
    protected abstract function assambleTheResources();
    
    /**
     * @return ILayout
     */
    protected abstract function assambleTheLayout();
    
    /**
     * @return User
     */
    protected function createUser() {
        $user = new User(null);
        return $user;
    }
    
    public function getName() {
        $name = get_class($this);
        $converter = new CaseConverter($name, CaseConverter::CASE_CAMEL);
        return $converter->toUrlCase();
    }
    
    public function __construct() {
        $resources = $this->assambleTheResources();
        if (! $resources instanceof ResourceCollection) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::assambleTheResources must return a ResourceCollection type'
            );
        };
        
        $user = $this->createUser();        
        if (! $user instanceof User) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::createUser must return a User'
            );
        };
        
        $this->engine = new QeyEngine($resources, $user);
        $this->params = $resources->getParams();
    }
    
    protected function getLayout() {
        $layout = $this->assambleTheLayout();
        if (! $layout instanceof ILayout) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::assambleTheLayout must return an ILayout'
            );
        };
        
        return $layout;
    }
    
    protected function getPages($defaultPage) {
        $pages = new PageFactoryCollection($defaultPage);
        
        $appPages = $this->getApplicationPageFactory();
        if (! $appPages instanceof IPageFactory) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::getApplicationPageFactory must return a IPageFactory'
            );
        };
        
        $pages->addFactory($appPages);
        return $pages;
    }
    
    public function createPage($defaultPage) {
        $layout = $this->getLayout();
        $pages = $this->getPages($defaultPage);
        
        return $this->engine->createPage($layout, $pages);
    }
    
    public function processRequest() {
        $resources = $this->engine->resources;
        $actions = new ActionFactoryCollection($resources->getParams());
        
        $appAction = $this->getApplicationActionFactory();
        if (! $appAction instanceof IActionFactory) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::getApplicationPageFactory must return a IPageFactory'
            );
        };
        
        $actions->addFactory( $appAction );
        $actions->addFactory( new QeyActionFactory($resources) );
        
        return $this->engine->processRequest($actions);
    }
}
?>
