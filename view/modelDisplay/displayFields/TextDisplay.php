<?php
namespace qeywork;

/**
 * @author Dexx
 */
class TextDisplay extends FieldDisplay {
    public function render() {
        return ($this->value === null) ? '' : $this->value . '';;
    }
}

?>
