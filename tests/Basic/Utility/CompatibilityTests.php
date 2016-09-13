<?php
namespace Swiftcore\Jose\Tests\Basic\Utility;

use Swiftcore\Jose\Tests\TestCase;

class CompatibilityTests extends TestCase
{
    public function testGmpImport()
    {
        $number = gmp_import("\0");
        $number = gmp_strval($number);
        $this->assertTrue('0' === $number);

        $number = gmp_import("\0\1\2");
        $number = gmp_strval($number);
        $this->assertTrue('258' === $number);

        $decimal = '34129995623412432326999562341299956234124323269995623412432326999562341243232699956234124323269995632';
        $hex = '3E6A8F3188DAB7B3F4961D12D57CF7BC069AC3F8B40D7560B6BD2B7B8A8295A55EFD3E5CDD963A196C70';
        $number = gmp_import(hex2bin($hex));
        $number = gmp_strval($number, 10);
        $this->assertEquals($decimal, $number);
    }

    public function testGmpExport()
    {
        $number = gmp_init(16705);
        $number = gmp_export($number);
        $this->assertTrue('AA' === $number);

        $binary = '10010100110101010000010110101100101011011010000110001111010101100000111000111100110101101110011001110001101000010111101110001011011111001011101101010011100100101011011100001110001110100101010100100010011001111101110100111110000111110110011000101000101011001001101100110001011100010010100010001000110000111100011111101110100010010010101000101101011001110101100110000111011110010010100110011011101001101010011001111011111001001011100111000111110010000111110010000010001110100001111011001010100010000111000101011011111100100011110100100101000111000011001111010101110110111010010001110010110100010111111111111000110101111011101101010001001';
        $decimal = '82890344192308492694100109293848842364710163467320364676481010299999999883747377183246127472346712943672173492191929384771672349736471943919237416728346712786412784672137642139784287914678921';
        $number = gmp_init($decimal);
        $number = gmp_strval($number, 2);
        $this->assertEquals($binary, $number);
    }
}