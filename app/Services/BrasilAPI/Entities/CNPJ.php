<?php

namespace App\Services\BrasilAPI\Entities;

class CNPJ
{
  public string $cnpj;
  public string $razaoSocial;
  public string $decricaoSituacaoCadastral;

  public function __construct(array $data)
  {
    $this->cnpj = $data['cnpj'];
    $this->razaoSocial = $data['razao_social'];
    $this->decricaoSituacaoCadastral = $data['descricao_situacao_cadastral'];
  }
}
