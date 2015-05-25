<?php
namespace qeywork;

class EditableEntityListView {
    protected $name;
    protected $entityList;
    protected $entityType;
    protected $entityManager;
    
    /**
     * Constructor of this class
     * @param string $name
     * @param EntityEntity $entity
     * @param CEntityManager $entityManager
     */
    public function __construct($name, $entityType, $entityManager=null, $entityList=null) {
        $this->entityType = is_string($entityType) ?
                new $entityType() : $entityType;
        if (! $this->entityType instanceof IEntity) {
            throw new ArgumentException('$entityType must be an IEntity');
    }
        $this->name = $name;
        $this->entityList = $entityList;
        $this->entityManager = $entityManager === null ? new CEntityManager($this->entityType) : $entityManager;
    }
    
    /**
     * @param IEntityListViewVisual $view
     * @return string 
     * TODO: chain together with listview->build to build a single row
     */
    public function build($view = 'BasicEntityListViewVisual')
    {
        Buffer::start();
        
        if (is_string($view)) {
            $view = new $view();
        }
        if (! $view instanceof IEntityListViewVisual) {
            throw new ArgumentException('viewVisual must be an instance of IEntityListViewVisual');
        }
        
        $listViewState = getListViewStateCollection()->get($this->name);
        
        if (isset($_REQUEST["qey-lv-sort"])) {
            $listViewState->sort = $_REQUEST["qey-lv-sort"];
        }
        if (isset($_REQUEST["qey-lv-filter"]) && is_array($_REQUEST["qey-lv-filter"])) {
            $listViewState->filter = $_REQUEST["qey-lv-filter"];
            foreach ($listViewState->filter as $filterField => &$filterValue) {
                if ($filterValue !== '0' && empty($filterValue)) {
                    unset($listViewState->filter[$filterField]);
                }
            }
        }
        
        getListViewStateCollection()->add($listViewState);

        $entityProxy = new SortableFilterableEntityProxy($this->entityType);
        $sortOptions = $entityProxy->getSortOptions();
        $sortOption = $view->sortDiv($sortOptions, $listViewState);
        $filterOptions = $entityProxy->getFilterOptions();
        $filterOption = $view->filterDiv($filterOptions, $listViewState); 
        
        $headerCells = array();
        $displayedKeys = array();
        
        $keys = $this->entityType->keys();
        foreach ($keys as $key) {
            $entity = $this->entityType->getFullFieldEntity($key);
            if (isset($entity['show']) && $entity['show'] === true || isset($entity['label'])) {
                $label = isset($entity['label']) ? $entity['label'] : '';
                
                $view->headerCell($label);
                $headerCells[] = Buffer::flush(false);
                
                $displayedKeys[] = $key;
            }
        }
        
        $view->header(implode('', $headerCells));
        $header = Buffer::flush(false);
        
        $parts = explode(' ', $listViewState->sort);
        if ((count($parts) > 1) && (strtoupper($parts[1]) === 'DESC' || $parts[0] == DB::ORDER_DESC)) {
            $sortDesc = DB::ORDER_DESC;
        } else {
            $sortDesc = null;
        }
        $sortBy = $parts[0];
        $entityList = $this->entityList === null ? $this->entityManager->getList($listViewState->filter, $sortBy, $sortDesc) : $this->entityList;
        
        $rows = array();
        foreach ($entityList as $entity) {
            $cells = array();
            foreach ($displayedKeys as $key) {
                $entity = $entity->getFullFieldEntity($key);
                
                $value = isset($entity['value']) ? $entity['value'] : '';

                if (isset($entity['input'])) {
                    switch ($entity['input']) {
                        case 'select':
                            if (isset($entity['options'])) {
                                $value = $entity['options'][$value];
                            } 
                            else if (isset($entity['datasource'])) {
                                $options = FormUtils::getDataForSelect(
                                    $entity['datasource']['source'],
                                    $entity['datasource']['entity'],
                                    $entity['datasource']['key'],
                                    $entity['datasource']['value']);
                                //TODO: default value ^
                                $value = (isset($options[$value])) ? $options[$value] : ' - ';
                            }
                            break;

                        case 'radio':
                        case 'checkbox':
                            $value = $entity['options'][$value];
                            break;

                        case 'file':
                            $value = 'not implemented';
                            break;

                        case 'file-list':
                            $value = 'not implemented';
                            break;
                    }
                }

                $view->cell($value);
                $cells[] = Buffer::flush(false);
            }
            
            $view->entry($entity->getId(), implode('', $cells));
            $rows[] = Buffer::flush(false);
        }
        
        $view->base($sortOption, $filterOption, $header, implode("\n", $rows));
        $data = Buffer::flush();
        return $data;
    }
}
