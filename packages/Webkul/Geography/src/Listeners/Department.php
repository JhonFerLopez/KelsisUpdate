<?php

namespace Webkul\Geography\Listeners;

class Department
{
    /**
     * After create.
     *
     * @param  \Webkul\Geography\Models\Department  $department
     * @return void
     */
    public function afterCreate($department)
    {
        $department->updateFullSlug();
    }

    /**
     * After update.
     *
     * @param  \Webkul\Geography\Models\Department  $department
     * @return void
     */
    public function afterUpdate($department)
    {
        $department->updateFullSlug();
    }
}
