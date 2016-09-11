#!/usr/bin/env php
<?php
ini_set('memory_limit', '512M');
define('BASE_PATH', __DIR__  . '/..');
require_once BASE_PATH . '/vendor/autoload.php';

use Swiftcore\Jose\JWK;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Element\Signature;

$results = [];
$jwkPrivate = JWK::create(
    new Headers(['kty' => 'RSA']),
    ['file' => BASE_PATH . '/tests/keys/rsa_private1.pem',
        'pwd' => '123123']
);
$jwkPublic = JWK::create(
    new Headers(['kty' => 'RSA']),
    ['file' => BASE_PATH . '/tests/keys/rsa_public1.pem']
);

$testdata = [
    '1KB' => [
        'payload' => openssl_random_pseudo_bytes(1024),
        'loop' => 1000,
    ],
    '100KB' => [
        'payload' => openssl_random_pseudo_bytes(1024 * 100),
        'loop' => 1000,
    ],
    '2MB' => [
        'payload' => openssl_random_pseudo_bytes(1024 * 1024 * 2),
        'loop' => 50,
    ],
    '10MB' => [
        'payload' => openssl_random_pseudo_bytes(1024 * 1024 * 10),
        'loop' => 20,
    ],
];
foreach ($testdata as $size => $data) {
    $methods = ['RS256', 'RS384', 'RS512'];
    foreach ($methods as $method) {
        output("Running signature {$method} signing with {$size} payload benchmark...");
        $start = microtime(true);
        for ($i = 0; $i < $data['loop']; $i++) {
            $headers = new Headers([
                'alg' => 'RS256',
                'b64' => true,
                'crit' => ['b64', 'alg']
            ]);
            $payload = new Payload($data['payload']);

            $jws = new JWS($jwkPrivate, $payload, $headers);
            $jws->sign();
        }
        $stop = microtime(true);
        output("Signature {$method} signing benchmark DONE!");
        $total = ($stop - $start) * 1000;

        $results[$size]['signature']['signing'][$method] = [
            'total' => $total,
            'loop' => $data['loop'],
        ];
    }

    // verifying
    $jws = [
        'RS256' => new JWS(
            $jwkPublic,
            new Payload($data['payload']),
            new Headers(['alg' => 'RS256', 'b64' => true, 'crit' => ['b64', 'alg']], true),
            new Signature('P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA')
        ),
        'RS384' => new JWS(
            $jwkPublic,
            new Payload($data['payload']),
            new Headers(['alg' => 'RS384', 'b64' => true, 'crit' => ['b64', 'alg']], true),
            new Signature('P-4dDr4dterZnq5KwprUK3eb1zSmIwF8ZNkNsV_QFyFELE6mBSbmyEwOanODjjpgoNnQwdT9ZJuNCQlT4pdYcqyrKxamUPSVOciNIbDYzFeDz5DFX71hn_J3kIRQiEIYGMufEXhgO8kzvMysZ0GJJnVWR2FHpHd5ihlIXowda5919WEHKpPbtNm_i1Guw06W9O9JyUJtFmxoDrK1RkaI-JhEHCfJiN0oKy0mYFS7LzfrqOTFt6l16T7AVjeovXH_j-_AQbn9ZmTy9PqlsdXHpKFipMCM59gpJqK9bzNDa92GUmMeadv5vtu2wZf9n818GVT8Xs7ECG7Dv1bWdpEyYg')
        ),
        'RS512' => new JWS(
            $jwkPublic,
            new Payload($data['payload']),
            new Headers(['alg' => 'RS512', 'b64' => true, 'crit' => ['b64', 'alg']], true),
            new Signature('ZuuSCKZ-xPWCeT1Fd3MPBdFDi2XG3sNd6uWK7A2EkBID2bWg1qy0vJpd6UkwS5lFbA2AgwH5aIvm7gwLwj1UxIroeWqJEJ5bhgakjfasG3X7DNhr3l6GLzRS97jLsy6OtEqkspiHgxhYLCZkJ2SKJ0dThyjBT2E1vyaaqhRRrMBITfFX_tYyqPuBb1hvvstXPN_MfAa6yvGa_4E627lsqLTbEtdTXPa6wxWV3cmGlwE3_tDInBU5d3HEKUMwlv7GLXHMwL-6TBhurqepSRzkD_DM6zyuzzH8fFBo0QmnwivHXkggsT-lTFBO_zhgi4NtCvpqsw-A2jV3huBViCLxzA')
        ),
    ];

    foreach ($methods as $method) {
        output("Running signature {$method} verifying benchmark...");
        $start = microtime(true);
        for ($i = 0; $i < $data['loop']; $i++) {
            // verify signature
            $result = $jws[$method]->verify();
        }
        $stop = microtime(true);
        output("Signature {$method} verifying benchmark DONE!");
        $total = ($stop - $start) * 1000;

        $results[$size]['signature']['verifying'][$method] = [
            'total' => $total,
            'loop' => $data['loop'],
        ];
    }
}




//	Algorithm	|	Average	|	Total		|	Count
//	---------	|	-------	|	-----		|	-----
//	RS256		|	0.123ms	|	11434.123ms	|	1000000
foreach ($results as $size => $tests) {
    foreach ($tests as $type => $actions) {
        output('### ' . ucwords($type) . " Benchmark of {$size} payload" . PHP_EOL);
        foreach ($actions as $action => $methods) {
            output("#### " . ucwords($action) . PHP_EOL);
            output("\tAlgorithm\t|\tAverage\t|\tTotal\t\t|\tCount");
            output("\t---------\t|\t-------\t|\t-----\t\t|\t-----");
            foreach ($methods as $method => $result) {
                $total = round($result['total'], 0);
                $count = $result['loop'];
                $average = round($result['total'] / $result['loop'], 3);
                output("\t{$method}\t\t|\t{$average} ms\t|\t{$total} ms\t|\t{$count}");
            }
            output(PHP_EOL);
        }
    }
}

function output($line)
{
    echo $line . PHP_EOL;
}