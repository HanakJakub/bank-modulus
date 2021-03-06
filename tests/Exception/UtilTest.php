<?php

namespace Cs278\BankModulus\Exception;

use Cs278\BankModulus\SortCode;

/**
 * @covers \Cs278\BankModulus\Exception\Util
 */
final class UtilTest extends \PHPUnit\Framework\TestCase
{
    /** @dataProvider dataMaskAccountNumber */
    public function testMaskAccountNumber($expected, $string)
    {
        $this->assertSame($expected, Util::maskAccountNumber($string));
    }

    public function dataMaskAccountNumber()
    {
        return [
            ['1******8', '12345678'],
            ['1*******', '1234567*'],
            ['******', '123456'],
        ];
    }

    /** @dataProvider dataMaskString */
    public function testMaskString($expected, $string, $length)
    {
        $this->assertSame($expected, Util::maskString($string, $length));
    }

    public function dataMaskString()
    {
        return [
            ['', '', 0],
            ['*', 'X', 0],
            ['****', '1234', 5],
            ['1*3', '123', 3],
            ['1**4', '1234', 4],
            ['X*************X', 'X5555555555555X', 4],
        ];
    }

    public function testMaskSortCode()
    {
        $this->assertSame('11-**-33', Util::maskSortCode(new SortCode('112233')));
    }
}
