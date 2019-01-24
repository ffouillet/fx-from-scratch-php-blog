<?php

namespace OCFram\Form\Field;

class TextField extends Field
{

    protected $cols;
    protected $rows;

    public function buildWidget()
    {

        $widget = '<div class="form-group-'.$this->getName().'">';

        if (!empty($this->errorMessage)) {
            $widget .= '<p class="form-error">' . $this->errorMessage . '</p>';
        }

        $widget .= '<label>' . $this->label . '</label><textarea name="' . $this->name . '"';

        if (!empty($this->cols)) {
            $widget .= ' cols="' . $this->cols . '"';
        }

        if (!empty($this->rows)) {
            $widget .= ' rows="' . $this->rows . '"';
        }

        $widget .= '>';

        if (!empty($this->value)) {
            $widget .= htmlspecialchars($this->value);
        }

        return $widget . '</textarea></div>';

    }

    public function setCols($cols)
    {

        $cols = (int)$cols;

        if ($cols > 0) {
            $this->cols = $cols;
        }
    }

    public function setRows($rows)
    {

        $rows = (int)$rows;

        if ($rows > 0) {
            $this->rows = $rows;
        }
    }

}