<?php
namespace qeywork;

/**
 * @author Dexx
 */
class EditableModelListViewVisual extends ModelListViewVisual {
    protected $editLink;
    protected $removeLink;
    
    public function __construct($editLink, $removeLink) {
        $this->editLink = $editLink;
        $this->removeLink = $removeLink;
    }
    
    public function header($headerCellList) {
?>
        <tr>
            <?php echo $headerCellList; ?>
            <th></th>
        </tr>
<?php
    }
    
    public function entry($id, $cells) {
        echo qeyNode('tr')->html(
            $cells .
            qeyNode('td')->cls('action')->html(
                qeyNode('a')->cls('action-edit')->href($this->editLink->dir($id))->text('Edit') .
                qeyNode('a')->cls('action-remove')->href($this->removeLink->field('id', $id))->text('Remove')
            )
        );
    }
}

?>
