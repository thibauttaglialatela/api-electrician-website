<?php

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context): Kernel 
{
    return new Kernel($context['APP_ENV'], $context['APP_DEBUG']);
};

