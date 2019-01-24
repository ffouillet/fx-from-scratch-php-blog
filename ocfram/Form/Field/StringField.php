<?php

namespace OCFram\Form\Field;

class StringField extends Field
{

    protected $maxLength;

    public function buildWidget()
    {

        $widget = '<div class="form-group-'.$this->getName().'">';

        if (!empty($this->errorMessage)) {
            $widget .= '<p class="form-error">' . $this->errorMessage . '</p>';
        }

        $widget .= '<label>' . $this->label . '</label><input type="text" name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (!empty($this->maxLength)) {
            $widget .= ' maxlength="' . $this->maxLength . '"';
        }

        return $widget .= ' /></div>';

    }


    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;

        if ($maxLength > 0) {
            $this->maxLength = $maxLength;
        } else {
            throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }
    }
}
