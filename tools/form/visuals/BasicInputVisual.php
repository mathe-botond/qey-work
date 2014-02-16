<?php
namespace qeywork;

class BasicInputVisual implements IInputVisual {
    public function modelConnector($name, $selectedItems, $sourceItems, $attr) {
        $selector = qeyNode('div')->attr($attr)
                ->cls('model-connector')
                ->attr('data-qey-input-type', 'model-connector');
        $selectedList = qeyNode('select')->multiple('multiple')
                ->cls('selected-items')->name($name . '[]')->size(5);
        foreach ($selectedItems as $key => $item) {
            $selectedList->append(
                qeyNode('option')->value($key)->text($item)
            );
        }
        $sourceList = qeyNode('select')->multiple('multiple')
                ->cls('source-items')->size(5);
        foreach ($sourceItems as $key => $item) {
            $sourceList->append(
                qeyNode('option')->value($key)->text($item)
            );
        }
        $selector->html(
            qeyNode('div')->cls('selector-box')->html(
                $selectedList .
                qeyNode('button')->cls('remove')->text('>>>')
            ) .
            qeyNode('div')->cls('selector-box')->html(
                $sourceList .
                qeyNode('button')->cls('add')->text('<<<')
            )
        );
        
        return $selector;
    }

    protected function multiInputEntry($input) {
        return qeyNode('div')->cls('multi-input-entry')->html(
            $input .
            qeyNode('button')->cls('multi-input-sub')->text('-')
        );
    }
    
    public function multiInput($inputList) {
        $container = qeyNode('div')->attr('data-qey-input-type', 'multi-input');
        $empty = $inputList['empty'];
        unset($inputList['empty']);
        if (empty($inputList)) {
            $inputList[] = $empty;
        }
        $container->append($this->multiInputEntry($empty)
            ->style('display: none;')->cls('multi-input-empty'));
               
        foreach ($inputList as $input) {
            $container->append(
                $this->multiInputEntry($input)
            );
        }
        $container->append(qeyNode('button')->cls('multi-input-add')->text('+'));
        return $container;
    }
}
?>
