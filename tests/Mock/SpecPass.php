<?php

namespace Cs278\BankModulus\Mock;

use Cs278\BankModulus\BankAccountNormalized;
use Cs278\BankModulus\Spec\SpecInterface;

final class SpecPass implements SpecInterface
{
    public function check(BankAccountNormalized $bankAccount)
    {
        return true;
    }
}
