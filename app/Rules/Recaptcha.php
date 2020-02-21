<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

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
     * 
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client(['base_uri' => 'https://google.com/recaptcha/api/']);

        $resp = $client->post('siteverify', [
            'query' => [
                'secret' => config('services.recaptcha.secret'),
                'response' => $value,
                'remoteip' => request()->ip()
            ]
        ]);
        return json_decode($resp->getBody())->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Verifikasi recaptcha gagal. Coba lagi.';
    }
}
