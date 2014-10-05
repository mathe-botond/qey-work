<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyWorkAssambler {
    /** @var \Dice\IoC */
    protected $ioc;
    
    public function __construct() {
        $this->ioc = new \Dice\Dice();
    }
    
    public function setCostumIoC(\Dice\IoC $ioc) {
        $this->ioc = $ioc;
    }
    
    protected function getGlobalSubstitutions() {
        return array(
            
        );
    }
    
    protected function getForcedInstances() {
        return array(Model::class);
    }
    
    public function setupBaseRule() {
        $this->ioc->getRuleBuilder(\Dice\Dice::MATCH_ALL)
            ->setShared(true)
            ->addSubstitutions($this->getGlobalSubstitutions())
            ->addNewInstances($this->getForcedInstances())
            ->save();
    }
    
    public function setupIoC(Locations $locations, Globals $globals) {
        $ioc = $this->ioc;
        
        $ioc->assign($locations);
        $ioc->assign($globals);
        $ioc->assign($ioc);
        
        $this->setupBaseRule();
        
        $ioc->getRuleBuilder(Logger::class)
            ->construct(array($locations->logFilePath))
            ->save();
        
        $ioc->getRuleBuilder(FormRenderer::class)
            ->addSubstitution(
                IFormVisual::class,
                FormVisualUsingList::class)
            ->save();
        
        $this->ioc->getRuleBuilder(QeyMeta::class)
            ->addSubstitution(
                ICssLinkCollection::class,
                new \Dice\Instance(CssLinkCollection::class))
            ->save();
    }
    
    public function createPageHandler() {
        return $this->ioc->create(PageHandler::class);
    }
    
    public function createActionHandler() {
        return $this->ioc->create(ActionsHandler::class);
    }
    
    public function setupIocForPageCreation(PageRouteCollection $pages) {
        $this->ioc->assign($pages);
    }
    
    public function createLayout($layoutClass = null) {
        if ($layoutClass == null) {
            $layoutClass = EmptyLayout::class;
        }
        return $this->ioc->create($layoutClass);
    }
    
    /**
     * @return \qeywork\PageFactoryCollection
     */
    public function createPageCollection() {
        return $this->ioc->create(PageFactoryCollection::class);
    }
    
    public function createPage($className) {
        return $this->ioc->create($className);
    }
    
    public function createAction($className) {
        return $this->ioc->create($className);
    }

    public function getIoC() {
        return $this->ioc;
    }

    public function configureDb(DBConfig $config) {
        $this->ioc->assign($config);
    }

}
