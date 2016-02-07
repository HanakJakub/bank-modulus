<?php

namespace Cs278\BankModulus\BankAccountNormalizer;

use Cs278\BankModulus\BankAccountInterface;
use Cs278\BankModulus\BankAccountNormalized;
use Cs278\BankModulus\SortCode;

final class CoOperativeBankNormalizer implements NormalizerInterface
{
    /** @return BankAccountInterface */
    public function normalize(BankAccountInterface $bankAccount)
    {
        return new BankAccountNormalized(
            $bankAccount,
            $bankAccount->getSortCode(),
            substr($bankAccount->getAccountNumber(), 0, 8)
        );
    }

    /** @return bool */
    public function supports(BankAccountInterface $bankAccount)
    {
        $accountNumber = $bankAccount->getAccountNumber();
        $sortCode = $bankAccount->getSortCode();

        return 10 === strlen($accountNumber)
            && $sortCode->isBetween(new SortCode('080000'), new SortCode('090000'))
            && !$sortCode->isBetween(new SortCode('083000'), new SortCode('084000')); // Exclude Citibank
    }
}
