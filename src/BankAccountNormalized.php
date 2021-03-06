<?php

namespace Cs278\BankModulus;

use Cs278\BankModulus\Internal\Assert;

final class BankAccountNormalized implements BankAccountInterface, BankAccountNormalizedInterface
{
    /** @var BankAccountInterface */
    private $bankAccount;

    /** @var SortCode */
    private $sortCode;

    /** @var string */
    private $accountNumber;

    /** @deprecated Use BankAccountNormalizedInterface::ACCOUNT_NUMBER_LENGTH instead. */
    const LENGTH = self::ACCOUNT_NUMBER_LENGTH;

    /**
     * @param BankAccountInterface $bankAccount
     * @param string|SortCode      $sortCode
     * @param string               $accountNumber
     */
    public function __construct(BankAccountInterface $bankAccount, $sortCode, $accountNumber)
    {
        if (!$sortCode instanceof SortCode) {
            Assert::string($sortCode, 'Sort code must be a string or instance of SortCode');

            $sortCode = SortCode::create($sortCode);
        }

        Assert::string($accountNumber, 'Account number must be a string');

        $this->bankAccount = $bankAccount;
        $this->sortCode = $sortCode;
        $this->accountNumber = StringUtil::removeNonDigits($accountNumber);

        if (self::ACCOUNT_NUMBER_LENGTH !== \strlen($this->accountNumber)) {
            throw Exception\AccountNumberInvalidException::create($accountNumber);
        }
    }

    /**
     * Create new instance from bank account object.
     *
     * @param BankAccountInterface $bankAccount Must be already normalized for this to work
     *
     * @return self
     */
    public static function createFromBankAccount(BankAccountInterface $bankAccount)
    {
        return new self(
            $bankAccount,
            $bankAccount->getSortCode(),
            $bankAccount->getAccountNumber()
        );
    }

    /** @return BankAccountInterface */
    public function getOriginalBankAccount()
    {
        return $this->bankAccount;
    }

    /** @return SortCode */
    public function getSortCode()
    {
        return $this->sortCode;
    }

    /** @return string */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /** @return string */
    public function __toString()
    {
        return $this->sortCode->format('%s%s%s').$this->accountNumber;
    }
}
