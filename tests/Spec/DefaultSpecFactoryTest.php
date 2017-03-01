<?php

namespace Cs278\BankModulus\Spec;

/**
 * @covers Cs278\BankModulus\Spec\DefaultSpecFactory
 */
final class DefaultSpecFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new DefaultSpecFactory();

        $spec = $factory->create();

        $this->assertInstanceOf('Cs278\\BankModulus\\Spec\\SpecInterface', $spec);
    }

    /**
     * @dataProvider dataCreateAtDate
     * @require function error_clear_last
     */
    public function testCreateAtDate($expectedSpec, \DateTime $now)
    {
        error_clear_last();

        $factory = @DefaultSpecFactory::withNow($now);
        $error = error_get_last();

        $this->assertArraySubset([
            'message' => 'Cs278\\BankModulus\\Spec\\DefaultSpecFactory::withNow() is for testing only!',
            'type' => E_USER_WARNING,
        ], $error);

        error_clear_last();

        // Run tests 5 times to ensure consistent results.
        for ($i = 0; $i < 5; ++$i) {
            $spec = $factory->create();

            $this->assertInstanceOf('Cs278\\BankModulus\\Spec\\SpecInterface', $spec);
            $this->assertInstanceOf('Cs278\\BankModulus\\Spec\\'.$expectedSpec, $spec);
        }
    }

    public function dataCreateAtDate()
    {
        return [
            ['VocaLinkV390', new \DateTime('2010-04-12')],
            ['VocaLinkV390', new \DateTime('2014-12-25')],
            ['VocaLinkV390', new \DateTime('2017-01-08')],
            ['VocaLinkV400', new \DateTime('2017-01-09')],
            ['VocaLinkV400', new \DateTime('2017-01-10')],
        ];
    }
}
