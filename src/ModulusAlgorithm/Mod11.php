<?php

namespace Cs278\BankModulus\ModulusAlgorithm;

final class Mod11 implements AlgorithmInterface
{
    private $result;

    public function __construct($input, $weights)
    {
        $this->result = array_sum(array_map(function ($a, $b) {
            return $a * $b;
        }, str_split($input), $weights));
    }

    public function quotient()
    {
        return \Cs278\BankModulus\intdiv($this->result, 11);
    }

    public function remainder()
    {
        return $this->result % 11;
    }

    public function check()
    {
        return 0 === $this->remainder();
    }
}
