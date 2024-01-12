<?php

namespace Tests\Feature\Rules;

use App\Rules\ValidCNPJ;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidCNPJTest extends TestCase
{
    /** @test */
    public function it_should_check_if_the_cnpj_is_valid_and_active()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/06990590000123' => Http::response(
                ['descricao_situacao_cadastral' => 'ATIVA'], 200),
        ]);

        $rule = new ValidCNPJ();

        $this->assertTrue(
            Validator::make(['cnpj' => '06990590000123'], ['cnpj' => $rule])->passes()
        );
    }

    /** @test */
    public function return_false_if_cnpj_is_not_found_or_situacao_cadastral_not_ativa()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/*' => Http::response(
                [], 404),

            'https://brasilapi.com.br/api/cnpj/v1/06990590000125' => Http::response(
                ['descricao_situacao_cadastral' => 'Inativa'], 200),
        ]);

        $rule = new ValidCNPJ();

        $this->assertFalse(
            Validator::make(['cnpj' => '98712387223'], ['cnpj' => $rule])->passes()
        );

        $this->assertFalse(
            Validator::make(['cnpj' => '06990590000125'], ['cnpj' => $rule])->passes()
        );
    }
}
