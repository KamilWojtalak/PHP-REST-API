<?php

namespace App\Src\Interfaces;

interface ApiInterface
{
    public function __construct($config);

    public function getFormatedUrl($url);

    public function checkIfValidResource($rest_resource, $allowed_resources);

    /** Check whether url starts properly */
    public function doesUrlStartWell($url);

    public function getUrlResource($url);

    public function getUrlAfterResource($url);
  
}