<?php

namespace Webkul\Preparation\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Preparation\Models\Preparation;
use Carbon\Carbon;

class PreparationObserver
{
    /**
     * Handle the Preparation "deleted" event.
     *
     * @param  \Webkul\Preparation\Contracts\Preparation  $preparation
     * @return void
     */
    public function deleted($preparation)
    {
        Storage::deleteDirectory('preparation/' . $preparation->id);
    }

    /**
     * Handle the Preparation "saved" event.
     *
     * @param  \Webkul\Preparation\Contracts\Preparation  $preparation
     * @return void
     */
    public function saved($preparation)
    {
        foreach ($preparation->children as $child) {
            $child->touch();
        }
    }
}