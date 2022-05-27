<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\ApiInterface;

abstract class Api implements ApiInterface
{
    private $_config = [];

    public function __construct($config)
    {
        $this->_config = $config;
    }

    public function getFormatedUrl($url)
    {
        /** Get URL withour query string */
        $urlWithoutQueryString = parse_url($url, PHP_URL_PATH);

        /** Count all characters */
        $length = strlen($urlWithoutQueryString) - 1;

        /** Check if last character is a slash */
        if (!$urlWithoutQueryString[$length] === '/') return $urlWithoutQueryString;

        /** If last character is a slash trim it */
        return rtrim($urlWithoutQueryString, '/');
    }

    public function checkIfValidResource($rest_resource, $allowed_resources)
    {
        if (!in_array($rest_resource, $allowed_resources)) CustomFunctions::displayError('Wrong resource name', 404);
    }

    /** Check whether url starts properly */
    public function doesUrlStartWell($url)
    {
        if(!str_starts_with($url, $this->_config['URL_BEGINNING'])) CustomFunctions::displayError('Invalid URL. Read the docs first');
    }

    public function getUrlResource($url) {
        /** Make url into chunks */
        $chunks = $this->_urlToArray($url);

        /** Get allowed resources */
        $allowedResources = $this->_getAllowedResources();

        /** Get resource index */
        $resourceIndex = $this->_config['URL_RESOURCE_INDEX'];

        /** Get resource from URL */
        $resource = $chunks[$resourceIndex];

        /** Check if there is a valid resource */
        if (in_array($resource, $allowedResources)) return $resource;

        CustomFunctions::displayError('Invalid resource. Read the docs first');
    }

    private function _getAllowedResources()
    {
        return $this->_config['URL_ALLOWED_RESOURCES'];
    }

    private function _urlToArray($url)
    {
        $chunks = explode($this->_config['URL_DELIMETER'], $url);

        /** Get rid of first empty array element */
        array_shift($chunks);

        return $chunks;
    }

    public function getUrlAfterResource($url) {
        $explodeUrl = explode('/', $url);
        return array_slice($explodeUrl, 4);
    }
}
