<?php

namespace OCFram\Config;

class ApplicationConfig {

    private $configVariables = [];
    private $configFilePath;

    const CONFIG_FILE_NOT_FOUND = 1;
    const CONFIG_VARIABLE_NOT_DEFINED = 2;

    public function __construct($configFilePath) {
        $this->setConfigFilePath($configFilePath);
        $this->loadConfigFile();
    }

    private function setConfigFilePath($configFilePath) {

        if (preg_match("#\.xml$#", $configFilePath) == false) {
            throw new \RuntimeException("Supported application configuration file format is XML.", self::CONFIG_FILE_NOT_FOUND);
        }

        if (!is_string($configFilePath) || !file_exists($configFilePath) || !is_file($configFilePath)) {
            throw new \RuntimeException("Application config file cannot be found", self::CONFIG_FILE_NOT_FOUND);
        }

        $this->configFilePath = $configFilePath;

    }

    private function loadConfigFile() {
        $xml = new \DOMDocument;
        $xml->load($this->configFilePath);

        $elements = $xml->getElementsByTagName('define');

        foreach ($elements as $element)
        {
            // If an element in the config file have a type of 'absolute_path', we prepend APPLICATION_ROOT_PATH to its value.
            if ($element->hasAttribute('type') && $element->getAttribute('type') == 'absolute_path') {
                $element->setAttribute('value', APPLICATION_ROOT_PATH . $element->getAttribute('value'));
            }

            $this->configVariables[$element->getAttribute('var')] = $element->getAttribute('value');
        }

    }

    public function getConfigVariable($configVariable) {
        if (!isset($this->configVariables[$configVariable])) {
            throw new \InvalidArgumentException("Configuration variable $configVariable is not defined in Application Configuration File", 2);
        }

        return $this->configVariables[$configVariable];
    }
}