<?php

namespace OCFram\Form\FormHandler;

use OCFram\Application;
use OCFram\Form\Form;

abstract class FormHandler {

    abstract public static function handle(Form $form, Application $app);
}