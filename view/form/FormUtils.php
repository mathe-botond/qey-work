<?php
namespace qeywork;

class FormUtils {
    /**
     * Get an array to fill select
     * @param string $source
     * @param ModelEntity $model
     * @param string $key
     * @param string $value
     * @throws ModelException
     * @return array data for select
     */
    public static function getDataForSelect($source, $model, $key, $value) {
        $db = getDb();
            $options = array();
            if ($source == 'db') {
                $result = $db->search($model); //retrieve all rows from table
                if (count($result) > 0 && 
                        (! $result[0]->hasKey($key)
                        || ! $result[0]->hasKey($value))) {
                    throw new ModelException($model . ' has no row ' . $key . ' or ' . $value);
                }
                foreach ($result as $resultModel) {
                    $tkey = ($key == 'id') ? $resultModel->getId() : $resultModel->$key;
                    $options[ $tkey ] = $resultModel->$value;
                }
            } else {
                throw new NotImplementedException('Only database datasource supported');
            }
            return $options;
    }
    
    public static function getDataForModelConnector($type, $params) {
        if ($type == 'db') {
            $conditions = isset($params['source-filter-by']) ?
                $params['source-filter-by']->getArray() : array();
            $sourceModels = getDb()->search($params['source-table'], $conditions);
            $conditions = array($params['left-id-field'] => $params['current-model-id']);            
            $selectedModels = getDb()->search($params['link-table'], $conditions);
            
            $field = $params['source-displayed-field'];
            $source = array();
            foreach ($sourceModels as $model) {
                $source[$model->getId()] = $model->$field;
            }
            
            $idField = $params['right-id-field'];
            $selected = array();
            foreach ($selectedModels as $model) {
                $id = $model->$idField;
                $selected[$id] = $source[$id];
                unset($source[$id]);
            }
            
            return array(
                'selected' => $selected,
                'source' => $source
            );
        } else {
            throw new NotImplementedException('Only database datasource supported');
        }
    }
    
    public static function getDataForMultiInputs($type, $params) {
        if ($type == 'db') {
            $conditions = array( 
                $params['foreign-id-field'] => $params['current-model-id']
            );
            $valueModels = getDb()->search($params['value-table'], $conditions);
            $valueField = $params['value-field'];
            $values = array();
            foreach ($valueModels as $model) {
                $values[] = $model->$valueField;
            }
            return $values;
        } else {
            throw new NotImplementedException('Only database datasource supported');
        }
    }
    
    public static function buildMultiField($model, $key) {
        
        $visual = new BasicInputVisual();
        $data = $model['datasource'];
        $values = FormUtils::getDataForMultiInputs($data['type'], $data);
        $inputList = array();
        foreach ($values as $value) {
            $modelCopy = clone($model);
            $inputList[] = self::buildInputField($modelCopy, $key, $value);
        }
        
        $inputList['empty'] = self::buildInputField($model, $key);
        return $visual->multiInput($inputList);
    }

    public static function buildInputField($model, $key, $value = null) {
        if (is_string($model->input)) {
            $inputType = $model->input;
            $model->input = new Descriptor();
        } else {
            $inputType = $model->input->type;
            unset($model->input->type);
        }
        
        if (isset($model->class)) $model->input->class .= " " . $model->class;
        if (isset($model->style)) $model->input->style .= " " . $model->style;
        $model->input->token = $key;
        
        if (isset($model['multiple']) && $model['multiple'] == true) {
            $model->input->token .= '[]';
        }
        
        $input = '';
        
        switch ($inputType) {
            case 'text':
            case 'wymeditor':
                $input = qeyNode('textarea')->attr($model->input->getRaw())->text(empty($value) ? "" : $value);
                break;
        
            case 'varchar':
            case 'date':
                $model->input->type = 'text';
                $model->input->value = $value;
                $input = qeyNode('input')->attr($model->input->getRaw());
                break;
        
            case 'password':
                $model->input->type = 'password';
                $model->input->value = $value;
                $input = qeyNode('input')->attr($model->input->getRaw());
                break;
        
            case 'select':
                $input = qeyNode('select')->attr($model->input->getRaw());
                
                if (isset($model['add-empty-field'])) {
                    $model->add_empty_field = $model['add-empty-field'];
                }
                if (isset($model->add_empty_field) && $model->add_empty_field) {
                    $input->append(qeyNode('option')->val(''));
                }
        
                if (isset($model['datasource'])) {
                    $options = FormUtils::getDataForSelect(
                            $model['datasource']['source'],
                            $model['datasource']['model'],
                            $model['datasource']['key'],
                            $model['datasource']['value']);
                } else if (isset($model['options'])) {
                    $options = $model['options'];
                } else {
                    $options = array(); //empty array
                }
        
                foreach ($options as $optionKey => $optionValue) {
                    $optionNode = qeyNode('option')->val($optionKey)->text($optionValue);
                    if ((string)$value === (string)$optionKey) {
                        $optionNode->selected('selected');
                    }
                    $input->append($optionNode);
                }
                break;
                
            case 'model-connector':
                $visual = new BasicInputVisual();
                $data = $model['datasource'];
                $options = FormUtils::getDataForModelConnector($data['type'], $data);
                $input = $visual->modelConnector(
                    $model->input->token,
                    $options['selected'],
                    $options['source'],
                    $model->input->getRaw()
                );
                break;
            case 'radio':
                $first = true;
                $model->input->type = 'radio';
                foreach ($model['options'] as $optionKey => $optionValue) {
                    $radioNode = qeyNode('input')->attr($model->input->getRaw())->val($optionKey);
        
                    if ((string)$value === (string)$optionKey || $first && empty($value)) {
                        //set value or set first item as selected
                        $radioNode->checked('checked');
                    }
                    $first = false;
        
                    $input .= $radioNode . $optionValue;
                }
                break;
        
            case 'checkbox':
                $model->input->type = 'checkbox';
                $model->input->token .= '[]';
                foreach ($model['options'] as $optionKey => $optionValue) {
                    $checkboxNode = qeyNode('input')->attr($model->input->getRaw())->val($optionKey);
        
                    if (! empty($value) &&
                            array_search((string)$optionKey, $value) !== false ) {
                        $checkboxNode->checked('checked');
                    }
        
                    $input .= $checkboxNode . $optionValue;
                }
                break;
        
            case 'file':
                $model->input->id = $key;
                $model->input->type = 'file';
                $model->input->value = $value;
                if (!empty($value)) $model->input->style = "display:none; $model->input->style";
                $input = qeyNode('input')->attr($model->input->getRaw());
                if (!empty($value)) {
                    $deleteLink =
                    qeyNode('a')->attr(array(
                            'href' => "javascript:document.getElementById('$key').style.display = '';document.getElementById('helper_$key').style.display = 'none';document.getElementById('hidden_$key').value = '';"
                    ))->html("delete");
                    $input .= qeyNode('span')->attr(array('id' => "helper_$key"))->html("$value ($deleteLink)");
                }
                $input .= qeyNode('input')->attr(array('id' => "hidden_$key", 'type' => 'hidden', 'name' => "hidden_$key", 'value' => $value));
                break;
        
            case 'file-list':
                $model->input->type = 'file';
                $model->input->multiple = 'true';
                $model->input->token .= '[]';
                for ($i = 0; $i < $model['count']; $i++)
                    $input .= qeyNode('input')->attr($model->input->getRaw());
                break;
        }
        
        if (isset($model['readonly']) && $model['readonly'] == true) {
            $input->readonly("true");
        }
        
        return $input;
    }
}
