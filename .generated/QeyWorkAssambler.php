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
        return array('\\qeywork\\Model');
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
        
        $ioc->getRuleBuilder('\\qeywork\\Logger')
            ->construct(array($locations->logFilePath))
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
    }
    
    public function createPageHandler() {
        return $this->ioc->create('\\qeywork\\PageHandler');
    }
    
    public function createActionHandler() {
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
