<?php
if (!function_exists('gmp_import')) {
    function gmp_import($data)
    {
        return gmp_init(bin2hex($data), 16);
    }
}

if (!function_exists('gmp_export')) {
    function gmp_export($gmpnumber)
    {
        return hex2bin(gmp_strval($gmpnumber, 16));
    }
}
