<?php

declare(strict_types=1);

namespace Cs278\BankModulus\Spec;

use Cs278\BankModulus\BankAccountNormalized;

interface SpecInterface
{
    public function check(BankAccountNormalized $bankAccount): bool;
}
