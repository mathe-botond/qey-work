<?php
namespace qeywork;

interface IEditableModelListViewVisual extends IVisual {
    /**
     * The skeleton of the view, it must use the given parameters
     * @param string $sort A &lt;div&gt that holds the sorting functionality
     * @param string $filter A &lt;div&gt that holds the filteriong functionality
     * @param string $header The list of table header cells
     * @param string $rows The list of rows of the table
     */
    public function base($sort, $filter, $header, $rows);
    
    /**
     * The &lt;div&gt; that will hold the list's sort functionality 
     * @param mixed $sortOptions
     * @param ListViewState $listViewState
     */
    public function sortDiv($sortOptions, $listViewState);
    
    /**
     * The &lt;div&gt; that will hold the list's sort functionality
     * @param mixed $filterOptions
     * @param ListViewState $listViewState
     */
    public function filterDiv($filterOptions, $listViewState);
    
    /**
     * Header of the modelListView
     * @param string $headerCellList
     */
    public function header($headerCellList);
    
    /**
     * A header cell
     * @param string $label of header
     */
    public function headerCell($label);
    
    /**
     * A line in the modelListView
     * @param mixed $id of entry
     * @param string $cells
     */
    public function entry($id, $cells);
    
    /**
     * A cell in list
     * @param string $value cell value
     */
    public function cell($value);
    
    /**
     * Add an action cell at the end of every row
     * TODO: make it more generic
     */
    public function actions();
}
