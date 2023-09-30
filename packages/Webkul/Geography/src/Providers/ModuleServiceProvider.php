<?php

namespace Webkul\Geography\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Geography\Models\Department::class,
        \Webkul\Geography\Models\town::class,
    ];
}