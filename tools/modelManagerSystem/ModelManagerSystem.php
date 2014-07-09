<?php
namespace qeywork;

/**
 * ModelManager is a graphical tool
 * to insert, edit or remove database entries based on a single model or view
 *
 * @author Dexx
 */
abstract class ModelManagerSystem implements IPage, IAction {
    const MODE_LIST = 'list';
    const MODE_EDIT = 'edit';   
    const MODE_ADD = 'add';
    const MODE_REMOVE = 'remove';
    
    const YOURE_AN_ACTION = 'action';
    const YOURE_A_PAGE = 'page';
    
    /** @var Locations */
    protected $locations;
    
    protected $mode;
    protected $id;
    
    /** @var IModelManagerFactory */
    private $factory;
    
    /** @var Page */
    private $page;
    
    /** @var IAction */
    private $action;

    /**
     * @param IModelManagerConfigurator $configurator
     * @param string $mode 
     */
    
    protected function constructAsPage() {
        switch ($this->mode) {
            case self::MODE_LIST:
                $this->page = $this->factory->getListingPage();
                break;
            case self::MODE_ADD:
            case self::MODE_EDIT:
                $this->page = $this->factory->getModelFormPage($this->mode, $this->id);
                break;
            default:
                throw new ClientDataException('Undefined operation');
        }
    }
    
    //When acting as a controller
    protected function constructAsAction() {
        
        $this->redirectAfterExecute = false;
        switch ($this->mode) {
            case self::MODE_ADD:
                $this->action = $this->factory->getInputAction();
                break;
            case self::MODE_EDIT:
                $this->action = $this->factory->getModifyAction();
                break;
            case self::MODE_REMOVE:
                $this->action = $this->factory->getRemovalAction();
                break;
            default:
                throw new ClientDataException('Undefined operation');
        }
    }
    
    public function __construct(
            Locations $locations,
            Params $params,
            IModelManagerFactory $factory,
            $whatAmI)
    {
        $this->locations = $locations;
        
        $args = $params->getArgs();
        $this->factory = $factory;
        $this->factory->createPathCollection(
                $locations->baseUrl,
                $this->getDefaultName());
        
        $mode = isset($args[0]) ? $args[0] : self::MODE_LIST;
        $this->id = isset($args[1]) ? $args[1] : -1;
        $this->mode = $mode;
        
        
        switch ($whatAmI) {
            case self::YOURE_AN_ACTION:
                $this->constructAsAction();
                break;
            case self::YOURE_A_PAGE:
                $this->constructAsPage();
                break;
        }
    }
    
    public function getTitle() {
        return $this->page->getTitle();
    }
    
    public function render() {
        return $this->page->render();
    }
    
    public function execute() {
        $this->action->execute();
        try {
            $this->locations->redirect($this->factory->getPathCollection()->listingPage);
        } catch (ResourceException $e) {
            //Pokemon exception: catch em and never talk about it
        }
    }
    
    public function getDefaultName() {
        $class = get_class($this);
        $explode = explode('\\', $class);
        $name = end($explode);
        $converter = new CaseConverter($name, CaseConverter::CASE_CAMEL);
        return $converter->toUrlCase();
    }
}
