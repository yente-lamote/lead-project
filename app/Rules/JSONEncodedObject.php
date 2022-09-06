<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class JSONEncodedObject implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $extraAttributes=json_decode($value);
        return is_object($extraAttributes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute needs to be a JSON string with an encoded object';
    }
}
