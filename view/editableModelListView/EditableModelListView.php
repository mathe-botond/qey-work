<?php
namespace qeywork;

class EditableModelListView {
    protected $name;
    protected $modelList;
    protected $modelType;
    protected $modelManager;
    
    /**
     * Constructor of this class
     * @param string $name
     * @param ModelEntity $model 
     * @param CModelManager $modelManager
     */
    public function __construct($name, $modelType, $modelManager=null, $modelList=null) {
        $this->modelType = is_string($modelType) ? 
                new $modelType() : $modelType;
        if (! $this->modelType instanceof IModel) {
            throw new ArgumentException('$modelType must be an IModel');
    }
        $this->name = $name;
        $this->modelList = $modelList;
        $this->modelManager = $modelManager === null ? new CModelManager($this->modelType) : $modelManager;
    }
    
    /**
     * @param IModelListViewVisual $view
     * @return string 
     * TODO: chain together with listview->build to build a single row
     */
    public function build($view = 'BasicModelListViewVisual')
    {
        Buffer::start();
        
        if (is_string($view)) {
            $view = new $view();
        }
        if (! $view instanceof IModelListViewVisual) {
            throw new ArgumentException('viewVisual must be an instance of IModelListViewVisual');
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

        $modelProxy = new SortableFilterableModelProxy($this->modelType);
        $sortOptions = $modelProxy->getSortOptions();
        $sortOption = $view->sortDiv($sortOptions, $listViewState);
        $filterOptions = $modelProxy->getFilterOptions();
        $filterOption = $view->filterDiv($filterOptions, $listViewState); 
        
        $headerCells = array();
        $displayedKeys = array();
        
        $keys = $this->modelType->keys();
        foreach ($keys as $key) {
            $model = $this->modelType->getFullFieldModel($key);
            if (isset($model['show']) && $model['show'] === true || isset($model['label'])) {
                $label = isset($model['label']) ? $model['label'] : '';
                
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
        $modelList = $this->modelList === null ? $this->modelManager->getList($listViewState->filter, $sortBy, $sortDesc) : $this->modelList;
        
        $rows = array();
        foreach ($modelList as $model) {
            $cells = array();
            foreach ($displayedKeys as $key) {
                $model = $model->getFullFieldModel($key);
                
                $value = isset($model['value']) ? $model['value'] : '';

                if (isset($model['input'])) {
                    switch ($model['input']) {
                        case 'select':
                            if (isset($model['options'])) {
                                $value = $model['options'][$value];
                            } 
                            else if (isset($model['datasource'])) {
                                $options = FormUtils::getDataForSelect(
                                    $model['datasource']['source'],
                                    $model['datasource']['model'],
                                    $model['datasource']['key'],
                                    $model['datasource']['value']);
                                //TODO: default value ^
                                $value = (isset($options[$value])) ? $options[$value] : ' - ';
                            }
                            break;

                        case 'radio':
                        case 'checkbox':
                            $value = $model['options'][$value];
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
            
            $view->entry($model->getId(), implode('', $cells));
            $rows[] = Buffer::flush(false);
        }
        
        $view->base($sortOption, $filterOption, $header, implode("\n", $rows));
        $data = Buffer::flush();
        return $data;
    }
}
