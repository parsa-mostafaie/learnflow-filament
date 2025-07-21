<?php
namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class LaravelViteNonceGenerator implements NonceGenerator
{
  public function generate(): string
  {
    Vite::useCspNonce();

    return Vite::cspNonce();
  }
}