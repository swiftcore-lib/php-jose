<?php
if (!function_exists('gmp_import')) {
    /**
     * GMP import from a binary string
     *
     * @param $data string numeric string which will convert to GMP number.
     * @return resource
     */
    function gmp_import($data)
    {
        return gmp_init(bin2hex($data), 16);
    }
}

if (!function_exists('gmp_export')) {
    /**
     * GMP export to a binary string
     *
     * @param $gmpnumber resource The GMP number that will be converted to a string.
     * @return string
     */
    function gmp_export($gmpnumber)
    {
        return hex2bin(gmp_strval($gmpnumber, 16));
    }
}
