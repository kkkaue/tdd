<?php

namespace App\Services\BrasilAPI;

use App\Exceptions\CNPJNotFound;
use App\Services\BrasilAPI\Entities\CNPJ;
use Illuminate\Support\Facades\Http;

class BrasilAPI
{
  public function cnpj(string $cnpj): CNPJ
  {
    $request = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

    if ($request->status() !== 200) {
      throw new CNPJNotFound();
    }

    return new CNPJ($request->json());
  }
}