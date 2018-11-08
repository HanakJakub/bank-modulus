<?php

declare(strict_types=1);

namespace Cs278\BankModulus;

final class BankAccount implements BankAccountInterface
{
    /** @var SortCode */
    private $sortCode;

    /** @var string */
    private $accountNumber;

    /**
     * @param SortCode|string $sortCode
     */
    public function __construct($sortCode, string $accountNumber)
    {
        if (!$sortCode instanceof SortCode) {
            Assert::string($sortCode, 'Sort code must be a string or instance of SortCode');

            $sortCode = SortCode::create($sortCode);
        }

        Assert::regex($accountNumber, '{^(?:.*\d.*){6}$}', 'Account number must contain at least 6 digits');

        $this->sortCode = $sortCode;
        $this->accountNumber = StringUtil::removeNonDigits($accountNumber);
    }

    /** @return SortCode */
    public function getSortCode(): SortCode
    {
        return $this->sortCode;
    }

    /** @return string */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }
}
