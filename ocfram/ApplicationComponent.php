<?php

namespace OCFram;

abstract class ApplicationComponent {

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getApp() {
        return $this->application;
    }
}