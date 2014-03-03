<?php
namespace qeywork;

interface IInputVisual {
    //TODO: Add all the inputs here
    public function modelConnector($name, $class, $selectedItems, $sourceItems);
    public function multiInput($inputList);
}
?>
