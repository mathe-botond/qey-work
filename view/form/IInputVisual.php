<?php
namespace qeywork;

interface IInputVisual {
    //TODO: Add all the inputs here
    public function entityConnector($name, $class, $selectedItems, $sourceItems);
    public function multiInput($inputList);
}
