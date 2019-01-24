<?php

namespace OCFram\Security;

class AccessControlParser {

    protected $accessControlFilePath;

    const ACCESS_CONTROL_FILE_NOT_FOUND = 1;
    const ACCESS_CONTROL_FILE_NOT_IN_XML_FORMAT = 2;

    public function __construct($accessControlFilePath)
    {
        $this->setAccessControlFilePath($accessControlFilePath);
    }

    public function getSecuredRoutesAndAreasFromAccessControlFile()
    {
        $xml = new \DOMDocument;
        $xml->load($this->accessControlFilePath);

        $securedRoutesXML = $xml->getElementsByTagName('securedRoute');
        $securedAreasXML = $xml->getElementsByTagName('securedArea');

        $securedRoutes = array();
        $securedAreas = array();

        foreach ($securedRoutesXML as $securedRoute) {
            $securedRoutes[] = new SecuredRoute(
                $securedRoute->getAttribute('name'),
                explode(',',$securedRoute->getAttribute('requiredRoles'))
            );

        }

        foreach ($securedAreasXML as $securedArea) {
            $securedAreas[] = new SecuredArea(
                $securedArea->getAttribute('urlPrefix'),
                explode(',',$securedArea->getAttribute('requiredRoles'))
            );
        }

        return ['securedRoutes' => $securedRoutes, 'securedAreas' => $securedAreas];
    }

    public function setAccessControlFilePath($accessControlFilePath)
    {

        if (!is_string($accessControlFilePath) || !file_exists($accessControlFilePath) || !is_file($accessControlFilePath)) {
            throw new \InvalidArgumentException("Access Control File not found");
        }

        if (preg_match("#\.xml$#", $accessControlFilePath) == 0) {
            throw new \RuntimeException("AccessControl File file is not in XML format as required",
                self::ACCESS_CONTROL_FILE_NOT_IN_XML_FORMAT);
        }

        $this->accessControlFilePath = $accessControlFilePath;
    }
}