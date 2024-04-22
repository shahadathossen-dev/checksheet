<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
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
        $secret_key = env('RECAPTCHA_SECRET_KEY');
        $responseObject = Http::post("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$value}");
        $response = $responseObject->body();
        return $response['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Recaptcha validation fails');
    }
}
