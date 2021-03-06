<?php
namespace QeyWork\View\ModelListView;

use QeyWork\View\Html\IHtmlObject;
use QeyWork\View\IVisual;

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
     * @param IHtmlObject|string $headerCellList
     * @return
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
     * @param IHtmlObject|string $cells
     * @return
     */
    public function entry($id, IHtmlObject $cells);
    
    /**
     * A cell in list
     * @param string $value cell value
     */
    public function cell($value);
}
