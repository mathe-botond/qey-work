<?php
namespace qeywork;

require dirname(__FILE__).'/common/common.php';
require dirname(__FILE__).'/common/Exceptions.php';
require dirname(__FILE__).'/Autoloader.php';

require dirname(__FILE__).'/tools/utils.php';

/**
 * @author Dexx
 */
abstract class QeyWork {
    /** @var Params */
    private $params;
    protected $engine;
    private $autoloader;

    /**
     * @return IPageFactory
     */
    protected abstract function getApplicationPageFactory();
    
    /**
     * @return IActionFactory
     */
    protected abstract function getApplicationActionFactory();
        
    /**
     * @return Resources
     */
    protected abstract function assambleTheResources();
    
    /**
     * @return ILayout
     */
    protected abstract function assambleTheLayout();
    
    /**
     * @return User
     */
    protected function createUser(Session $session) {
        $user = new User($session);
        return $user;
    }
    
    public function getName() {
        $name = get_class($this);
        $converter = new CaseConverter($name, CaseConverter::CASE_CAMEL);
        return $converter->toUrlCase();
    }
    
    public function __construct() {
        $this->autoloader = new Autoloader(__NAMESPACE__, __DIR__, 'qeyWork');
        
        $resources = $this->assambleTheResources();
        if (! $resources instanceof Resources) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::assambleTheResources must return a Resources type'
            );
        }
        
        $user = $this->createUser($resources->getSession());        
        if (! $user instanceof User) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::createUser must return a User'
            );
        }
        
        $this->engine = new QeyEngine($resources, $user);
        $this->params = $resources->getParams();
    }
    
    protected function getLayout() {
        $layout = $this->assambleTheLayout();
        if (! $layout instanceof ILayout) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::assambleTheLayout must return an ILayout'
            );
        }
        
        return $layout;
    }
    
    protected function getPages($defaultPage) {
        $pages = new PageFactoryCollection($defaultPage);
        
        $appPages = $this->getApplicationPageFactory();
        if (! $appPages instanceof IPageFactory) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::getApplicationPageFactory must return a IPageFactory'
            );
        }
        
        $pages->addFactory($appPages);
        return $pages;
    }
    
    public function createPage($defaultPage) {
        $layout = $this->getLayout();
        $pages = $this->getPages($defaultPage);
        
        return $this->engine->createPage($layout, $pages);
    }
    
    public function processRequest() {
        $resources = $this->engine->getResources();
        $actions = new ActionFactoryCollection($resources->getParams());
        
        $appAction = $this->getApplicationActionFactory();
        if (! $appAction instanceof IActionFactory) {
            throw new ApplicationException(
                'Implementation of QeyWebsite::getApplicationActionFactory must return an IActionFactory'
            );
        }
        
        $actions->addFactory( $appAction );
        $actions->addFactory( new QeyActionFactory($resources) );
        
        return $this->engine->processRequest($actions);
    }
}
