<?php
namespace Swiftcore\Jose;

final class RsaKey extends JWK
{
    public function __construct($headers, $key = null)
    {
        $this->headers = $headers;

        $passphrase = null;
        $key = array_values($key);
        $count = count($key);
        if (file_exists($key[0])) {
            $key[0] = file_get_contents($key[0]);
        }
        $content = $key[0];

        if ($count === 2) {
            $passphrase = $key[1];
        }

        $this->headers['alg'] = 'RS256';
        $this->key = $this->load($content, $passphrase);
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
