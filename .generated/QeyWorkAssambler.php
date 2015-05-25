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
        return array('\\qeywork\\Entity');
    }
    
    public function setupBaseRule() {
        $this->ioc->getRuleBuilder(\Dice\Dice::MATCH_ALL)
            ->setShared(true)
            ->addSubstitutions($this->getGlobalSubstitutions())
            ->addNewInstances($this->getForcedInstances())
            ->save();
    }
    
    public function setupIoC(Locations $locations, Globals $globals, $appName) {
        $ioc = $this->ioc;
        
        $ioc->assign($locations);
        $ioc->assign($globals);
        $ioc->assign($ioc);
        
        $this->setupBaseRule();
        
        $ioc->getRuleBuilder('\\qeywork\\Logger')
            ->setConstructParams(array($locations->logFilePath))
            ->save();
        
        $ioc->getRuleBuilder('\\qeywork\\FormRenderer')
            ->addSubstitution(
                '\\qeywork\\IFormVisual',
                '\\qeywork\\FormVisualUsingList')
            ->save();
        
        $this->ioc->getRuleBuilder('\\qeywork\\QeyMeta')
            ->addSubstitution(
                '\\qeywork\\ICssLinkCollection',
                new \Dice\Instance('\\qeywork\\CssLinkCollection'))
            ->save();

        $this->ioc->getRuleBuilder('\\qeywork\\Session')
            ->setConstructParams(array($appName))
            ->save();
    }
    
    /**
     * @return PageHandler
     */
    public function getPageHandler() {
        return $this->ioc->create('\\qeywork\\PageHandler');
    }
    
    public function registerPagePostProcessor($processorClass) {
        $processor = $this->ioc->create($processorClass);
        $pageHandler = $this->getPageHandler();
        $pageHandler->setPagePostProcessor($processor);
    }
    
    public function getActionHandler() {
        return $this->ioc->create('\\qeywork\\ActionsHandler');
    }
    
    public function setupIocForPageCreation(PageRouteCollection $pages) {
        $this->ioc->assign($pages);
    }
    
    public function createLayout($layoutClass = null) {
        if ($layoutClass == null) {
            $layoutClass = '\\qeywork\\EmptyLayout';
        }
        return $this->ioc->create($layoutClass);
    }
    
    /**
     * @return \qeywork\PageFactoryCollection
     */
    public function createPageCollection() {
        return $this->ioc->create('\\qeywork\\PageFactoryCollection');
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
