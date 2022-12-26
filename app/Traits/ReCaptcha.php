<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ReCaptcha
{
  /**
   * reCaptcha Google verify
   */
  protected function verifyCaptcha($value)
  {
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
      'secret' => config('recaptcha.site_secret'),
      'response' => $value,
    ]);
    
    return $response->json()['success'];
  }
}