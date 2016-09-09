<?php
namespace Swiftcore\Jose;

use Swiftcore\Base64Url;

final class RsaKey extends JWK
{
    public function __construct($headers, $key = [])
    {
        $this->headers = $headers;

        $key = array_values($key);
        $content = file_get_contents($key[0]);
        $this->headers['alg'] = 'RS256';
        $this->key = $this->load($content, $key[1]);
    }

    private function load($pem, $passphrase = '')
    {
        $res = openssl_pkey_get_private($pem, $passphrase);
        if (false === $res) {
            $res = openssl_pkey_get_public($pem);
        }

        return $res;
    }
}
