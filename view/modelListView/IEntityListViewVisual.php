<?php
namespace qeywork;

interface IEntityListViewVisual extends IVisual {
    /**
     * The skeleton of the view, it must use the given parameters
     * @param string $sort A &lt;div&gt that holds the sorting functionality
     * @param string $filter A &lt;div&gt that holds the filteriong functionality
     * @param string $header The list of table header cells
     * @param string $rows The list of rows of the table
     */
    public function base(IHtmlObject $header, IHtmlObject $rows);
    
    /**
     * Header of the entityListView
     * @param string $headerCellList
     */
    public function header(IHtmlObject $headerCellList);
    
    /**
     * A header cell
     * @param string $label of header
     */
    public function headerCell($label);
    
    /**
     * A line in the entityListView
     * @param mixed $id of entry
     * @param string $cells
     */
    public function entry($id, IHtmlObject $cells);
    
    /**
     * A cell in list
     * @param string $value cell value
     */
    public function cell($value);
}
