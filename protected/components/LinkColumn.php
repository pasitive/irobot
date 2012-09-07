<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 10/17/11
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class LinkColumn extends CLinkColumn
{

    public $imageHtmlOptions = array();

    public $imageUrlExpression = null;

    /**
     * Renders the data cell content.
     * This method renders a hyperlink in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data)
    {
        if ($this->urlExpression !== null)
            $url = $this->evaluateExpression($this->urlExpression, array('data' => $data, 'row' => $row));
        else
            $url = $this->url;
        if ($this->labelExpression !== null)
            $label = $this->evaluateExpression($this->labelExpression, array('data' => $data, 'row' => $row));
        else
            $label = $this->label;
        
        $options = $this->linkHtmlOptions;


        if ($this->imageUrlExpression !== null) {
            $imageUrl = $this->evaluateExpression($this->imageUrlExpression, array('data' => $data, 'row' => $row));
        } else {
            $imageUrl = $this->imageUrl;
        }

        if (is_string($this->imageUrl) || $this->imageUrlExpression !== null) {
            echo CHtml::link(CHtml::image($imageUrl, $label, $this->imageHtmlOptions), $url, $options);
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }
}
