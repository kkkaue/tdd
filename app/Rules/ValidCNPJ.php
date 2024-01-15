<?php

namespace App\Rules;

use App\Services\BrasilAPI\BrasilAPI;
use App\Services\BrasilAPI\Enum\CNPJSituacaoCadastral;
use App\Services\BrasilAPI\Exceptions\CNPJNotFound;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidCNPJ implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $cnpj = (new BrasilAPI)->cnpj($value);
            
            if ($cnpj->isActive() === false) {
                $fail("O CNPJ {$value} não está ativo.");
            }
        } catch (CNPJNotFound $e) {
            $fail("O CNPJ {$value} não foi encontrado.");
        }
    }
}
