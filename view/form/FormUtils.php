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
    
    public static function buildMultiField($record, $key) {
        
        $visual = new BasicInputVisual();
        $data = $record['datasource'];
        $values = FormUtils::getDataForMultiInputs($data['type'], $data);
        $inputList = array();
        foreach ($values as $value) {
            $recordCopy = clone($record);
            $inputList[] = self::buildInputField($recordCopy, $key, $value);
        }
        
        $inputList['empty'] = self::buildInputField($record, $key);
        return $visual->multiInput($inputList);
    }

    public static function buildInputField($record, $key, $value = null) {
        if (is_string($record->input)) {
            $inputType = $record->input;
            $record->input = new Descriptor();
        } else {
            $inputType = $record->input->type;
            unset($record->input->type);
        }
        
        if (isset($record->class)) $record->input->class .= " " . $record->class;
        if (isset($record->style)) $record->input->style .= " " . $record->style;
        $record->input->token = $key;
        
        if (isset($record['multiple']) && $record['multiple'] == true) {
            $record->input->token .= '[]';
        }
        
        $input = '';
        
        switch ($inputType) {
            case 'text':
            case 'wymeditor':
                $input = qeyNode('textarea')->attr($record->input->getRaw())->text(empty($value) ? "" : $value);
                break;
        
            case 'varchar':
            case 'date':
                $record->input->type = 'text';
                $record->input->value = $value;
                $input = qeyNode('input')->attr($record->input->getRaw());
                break;
        
            case 'password':
                $record->input->type = 'password';
                $record->input->value = $value;
                $input = qeyNode('input')->attr($record->input->getRaw());
                break;
        
            case 'select':
                $input = qeyNode('select')->attr($record->input->getRaw());
                
                if (isset($record['add-empty-field'])) {
                    $record->add_empty_field = $record['add-empty-field'];
                }
                if (isset($record->add_empty_field) && $record->add_empty_field) {
                    $input->append(qeyNode('option')->val(''));
                }
        
                if (isset($record['datasource'])) {
                    $options = FormUtils::getDataForSelect(
                            $record['datasource']['source'],
                            $record['datasource']['model'],
                            $record['datasource']['key'],
                            $record['datasource']['value']);
                } else if (isset($record['options'])) {
                    $options = $record['options'];
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
                $data = $record['datasource'];
                $options = FormUtils::getDataForModelConnector($data['type'], $data);
                $input = $visual->modelConnector(
                    $record->input->token,
                    $options['selected'],
                    $options['source'],
                    $record->input->getRaw()
                );
                break;
            case 'radio':
                $first = true;
                $record->input->type = 'radio';
                foreach ($record['options'] as $optionKey => $optionValue) {
                    $radioNode = qeyNode('input')->attr($record->input->getRaw())->val($optionKey);
        
                    if ((string)$value === (string)$optionKey || $first && empty($value)) {
                        //set value or set first item as selected
                        $radioNode->checked('checked');
                    }
                    $first = false;
        
                    $input .= $radioNode . $optionValue;
                }
                break;
        
            case 'checkbox':
                $record->input->type = 'checkbox';
                $record->input->token .= '[]';
                foreach ($record['options'] as $optionKey => $optionValue) {
                    $checkboxNode = qeyNode('input')->attr($record->input->getRaw())->val($optionKey);
        
                    if (! empty($value) &&
                            array_search((string)$optionKey, $value) !== false ) {
                        $checkboxNode->checked('checked');
                    }
        
                    $input .= $checkboxNode . $optionValue;
                }
                break;
        
            case 'file':
                $record->input->id = $key;
                $record->input->type = 'file';
                $record->input->value = $value;
                if (!empty($value)) $record->input->style = "display:none; $record->input->style";
                $input = qeyNode('input')->attr($record->input->getRaw());
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
                $record->input->type = 'file';
                $record->input->multiple = 'true';
                $record->input->token .= '[]';
                for ($i = 0; $i < $record['count']; $i++)
                    $input .= qeyNode('input')->attr($record->input->getRaw());
                break;
        }
        
        if (isset($record['readonly']) && $record['readonly'] == true) {
            $input->readonly("true");
        }
        
        return $input;
    }
}
