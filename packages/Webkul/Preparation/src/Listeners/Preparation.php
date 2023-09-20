<?php

namespace Webkul\Preparation\Listeners;

class Preparation
{
    /**
     * After create.
     *
     * @param  \Webkul\Preparation\Models\Preparation  $preparation
     * @return void
     */
    public function afterCreate($preparation)
    {
        $preparation->updateFullSlug();
    }

    /**
     * After update.
     *
     * @param  \Webkul\Preparation\Models\Preparation  $preparation
     * @return void
     */
    public function afterUpdate($preparation)
    {
        $preparation->updateFullSlug();
    }
}
