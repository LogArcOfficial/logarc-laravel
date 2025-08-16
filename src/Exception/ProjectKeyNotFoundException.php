<?php

namespace Dipesh79\LogArcLaravel\Exception;

use Exception;

class ProjectKeyNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Project key not found. Please set the project key in the configuration file.');
    }

}
