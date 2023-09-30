<?php

namespace Webkul\Geography\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Geography\Models\Town;
use Carbon\Carbon;

class TownObserver
{
    /**
     * Handle the Town "deleted" event.
     *
     * @param  \Webkul\Geography\Contracts\Town  $geography
     * @return void
     */
    public function deleted($geography)
    {
        Storage::deleteDirectory('geography/' . $geography->id);
    }

    /**
     * Handle the Town "saved" event.
     *
     * @param  \Webkul\Geography\Contracts\Town  $geography
     * @return void
     */
    public function saved($geography)
    {
        foreach ($geography->children as $child) {
            $child->touch();
        }
    }
}