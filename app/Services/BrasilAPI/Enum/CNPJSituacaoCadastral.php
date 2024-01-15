<?php

namespace App\Services\BrasilAPI\Enum;

enum CNPJSituacaoCadastral: string
{
  case ATIVA = 'ATIVA';
  case INATIVA = 'INATIVA';
}