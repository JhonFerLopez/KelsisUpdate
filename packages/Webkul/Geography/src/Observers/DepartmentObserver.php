<?php

namespace Webkul\Geography\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Geography\Models\Department;
use Carbon\Carbon;

class DepartmentObserver
{
    /**
     * Handle the Department "deleted" event.
     *
     * @param  \Webkul\Geography\Contracts\Department  $geography
     * @return void
     */
    public function deleted($geography)
    {
        Storage::deleteDirectory('geography/' . $geography->id);
    }

    /**
     * Handle the Department "saved" event.
     *
     * @param  \Webkul\Geography\Contracts\Department  $geography
     * @return void
     */
    public function saved($geography)
    {
        foreach ($geography->children as $child) {
            $child->touch();
        }
    }
}