<?php

namespace Webkul\Customer\Contracts\Validations;

use Illuminate\Contracts\Validation\Rule;

class Prefix implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[a-zA-Z]{1,2}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('customer::validation.prefix');
    }
}