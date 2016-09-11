<?php
define('BASE_PATH', __DIR__ . '/php-jose');
require_once BASE_PATH . '/../vendor/autoload.php';

$loop = 10000;
$start = microtime(true);
for ($i = 0; $i < $loop; $i++) {
    $jwk = \Swiftcore\Jose\JWK::create(
        new \Swiftcore\Jose\Element\Headers(['kty' => 'RSA']),
        ['file' => BASE_PATH . '/keys/rsa_private1.pem',
        'pwd' => '123123']
    );
    $headers = new \Swiftcore\Jose\Element\Headers([
        'alg' => 'RS256',
        'b64' => true,
        'crit' => ['b64', 'alg']
    ]);
    $payload = new \Swiftcore\Jose\Element\Payload(
        json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true])
    );

    $jws = new \Swiftcore\Jose\JWS($jwk, $payload, $headers);
    $jws->sign();
}
$stop = microtime(true);
$total = ($stop - $start) * 1000;
$average = $total / $loop;
echo "Execution Time - Total: {$total}ms Average: {$average}ms";
