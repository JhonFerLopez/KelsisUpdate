<?php

namespace Webkul\Preparation\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.preparation.create.after'  => [
            'Webkul\Preparation\Listeners\Preparation@afterCreate',
        ],

        'catalog.preparation.update.after'  => [
            'Webkul\Preparation\Listeners\Preparation@afterUpdate',
        ],
    ];
}
