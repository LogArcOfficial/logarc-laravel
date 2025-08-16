<?php

namespace Dipesh79\LogArcLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class LogArc extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'logarc';
    }
}
