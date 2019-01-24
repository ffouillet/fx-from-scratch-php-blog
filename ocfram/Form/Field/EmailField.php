<?php

namespace OCFram\Form\Field;

class EmailField extends Field
{

    public function buildWidget()
    {

        $widget = '<div class="form-group-'.$this->getName().'">';

        if (!empty($this->errorMessage)) {
            $widget .= '<p class="form-error">' . $this->errorMessage . '</p>';
        }

        $widget .= '<label>' . $this->label . '</label><input type="email" name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        return $widget .= ' /></div>';

    }

}
