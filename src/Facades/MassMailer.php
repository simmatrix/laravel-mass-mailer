<?php

namespace Simmatrix\MassMailer\Facades;

use Illuminate\Support\Facades\Facade;

class MassMailer extends Facade
{
    protected static function getFacadeAccessor() 
    {
        return 'MassMailer';
    }
}
?>
