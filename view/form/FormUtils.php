<?php
namespace qeywork;

class FormUtils {
    /**
     * Get an array to fill select
     * @param string $source
     * @param EntityEntity $entity
     * @param string $key
     * @param string $value
     * @throws EntityException
     * @return array data for select
     */
    public static function getDataForSelect($source, $entity, $key, $value) {
        $db = getDb();
            $options = array();
            if ($source == 'db') {
                $result = $db->search($entity); //retrieve all rows from table
                if (count($result) > 0 && 
                        (! $result[0]->hasKey($key)
                        || ! $result[0]->hasKey($value))) {
                    throw new EntityException($entity . ' has no row ' . $key . ' or ' . $value);
                }
                foreach ($result as $resultEntity) {
                    $tkey = ($key == 'id') ? $resultEntity->getId() : $resultEntity->$key;
                    $options[ $tkey ] = $resultEntity->$value;
                }
            } else {
                throw new NotImplementedException('Only database datasource supported');
            }
            return $options;
    }
    
    public static function getDataForEntityConnector($type, $params) {
        if ($type == 'db') {
            $conditions = isset($params['source-filter-by']) ?
                $params['source-filter-by']->getArray() : array();
            $sourceEntitys = getDb()->search($params['source-table'], $conditions);
            $conditions = array($params['left-id-field'] => $params['current-entity-id']);
            $selectedEntitys = getDb()->search($params['link-table'], $conditions);
            
            $field = $params['source-displayed-field'];
            $source = array();
            foreach ($sourceEntitys as $entity) {
                $source[$entity->getId()] = $entity->$field;
            }
            
            $idField = $params['right-id-field'];
            $selected = array();
            foreach ($selectedEntitys as $entity) {
                $id = $entity->$idField;
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
                $params['foreign-id-field'] => $params['current-entity-id']
            );
            $valueEntitys = getDb()->search($params['value-table'], $conditions);
            $valueField = $params['value-field'];
            $values = array();
            foreach ($valueEntitys as $entity) {
                $values[] = $entity->$valueField;
            }
            return $values;
        } else {
            throw new NotImplementedException('Only database datasource supported');
        }
    }
    
    public static function buildMultiField($entity, $key) {
        
        $visual = new BasicInputVisual();
        $data = $entity['datasource'];
        $values = FormUtils::getDataForMultiInputs($data['type'], $data);
        $inputList = array();
        foreach ($values as $value) {
            $entityCopy = clone($entity);
            $inputList[] = self::buildInputField($entityCopy, $key, $value);
        }
        
        $inputList['empty'] = self::buildInputField($entity, $key);
        return $visual->multiInput($inputList);
    }

    public static function buildInputField($entity, $key, $value = null) {
        if (is_string($entity->input)) {
            $inputType = $entity->input;
            $entity->input = new Descriptor();
        } else {
            $inputType = $entity->input->type;
            unset($entity->input->type);
        }
        
        if (isset($entity->class)) $entity->input->class .= " " . $entity->class;
        if (isset($entity->style)) $entity->input->style .= " " . $entity->style;
        $entity->input->token = $key;
        
        if (isset($entity['multiple']) && $entity['multiple'] == true) {
            $entity->input->token .= '[]';
        }
        
        $input = '';
        
        switch ($inputType) {
            case 'text':
            case 'wymeditor':
                $input = qeyNode('textarea')->attr($entity->input->getRaw())->text(empty($value) ? "" : $value);
                break;
        
            case 'varchar':
            case 'date':
                $entity->input->type = 'text';
                $entity->input->value = $value;
                $input = qeyNode('input')->attr($entity->input->getRaw());
                break;
        
            case 'password':
                $entity->input->type = 'password';
                $entity->input->value = $value;
                $input = qeyNode('input')->attr($entity->input->getRaw());
                break;
        
            case 'select':
                $input = qeyNode('select')->attr($entity->input->getRaw());
                
                if (isset($entity['add-empty-field'])) {
                    $entity->add_empty_field = $entity['add-empty-field'];
                }
                if (isset($entity->add_empty_field) && $entity->add_empty_field) {
                    $input->append(qeyNode('option')->val(''));
                }
        
                if (isset($entity['datasource'])) {
                    $options = FormUtils::getDataForSelect(
                            $entity['datasource']['source'],
                            $entity['datasource']['entity'],
                            $entity['datasource']['key'],
                            $entity['datasource']['value']);
                } else if (isset($entity['options'])) {
                    $options = $entity['options'];
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
                
            case 'entity-connector':
                $visual = new BasicInputVisual();
                $data = $entity['datasource'];
                $options = FormUtils::getDataForEntityConnector($data['type'], $data);
                $input = $visual->entityConnector(
                    $entity->input->token,
                    $options['selected'],
                    $options['source'],
                    $entity->input->getRaw()
                );
                break;
            case 'radio':
                $first = true;
                $entity->input->type = 'radio';
                foreach ($entity['options'] as $optionKey => $optionValue) {
                    $radioNode = qeyNode('input')->attr($entity->input->getRaw())->val($optionKey);
        
                    if ((string)$value === (string)$optionKey || $first && empty($value)) {
                        //set value or set first item as selected
                        $radioNode->checked('checked');
                    }
                    $first = false;
        
                    $input .= $radioNode . $optionValue;
                }
                break;
        
            case 'checkbox':
                $entity->input->type = 'checkbox';
                $entity->input->token .= '[]';
                foreach ($entity['options'] as $optionKey => $optionValue) {
                    $checkboxNode = qeyNode('input')->attr($entity->input->getRaw())->val($optionKey);
        
                    if (! empty($value) &&
                            array_search((string)$optionKey, $value) !== false ) {
                        $checkboxNode->checked('checked');
                    }
        
                    $input .= $checkboxNode . $optionValue;
                }
                break;
        
            case 'file':
                $entity->input->id = $key;
                $entity->input->type = 'file';
                $entity->input->value = $value;
                if (!empty($value)) $entity->input->style = "display:none; $entity->input->style";
                $input = qeyNode('input')->attr($entity->input->getRaw());
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
                $entity->input->type = 'file';
                $entity->input->multiple = 'true';
                $entity->input->token .= '[]';
                for ($i = 0; $i < $entity['count']; $i++)
                    $input .= qeyNode('input')->attr($entity->input->getRaw());
                break;
        }
        
        if (isset($entity['readonly']) && $entity['readonly'] == true) {
            $input->readonly("true");
        }
        
        return $input;
    }
}
