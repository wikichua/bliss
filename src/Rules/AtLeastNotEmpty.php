<?php

namespace Wikichua\Bliss\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastNotEmpty implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected int $number = 1)
    {
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
        $value = is_null($value) ? [] : $value;

        return count(array_filter($value ?? [])) >= $this->number;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please fill or select at least :number in :attribute:.', ['number' => $this->number]);
    }
}
