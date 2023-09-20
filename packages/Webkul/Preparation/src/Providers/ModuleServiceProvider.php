<?php

namespace Webkul\Preparation\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Preparation\Models\Preparation::class,
        \Webkul\Preparation\Models\PreparationTranslation::class,
    ];
}