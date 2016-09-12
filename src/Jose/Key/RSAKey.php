<?php
namespace Swiftcore\Jose\Key;

use Swiftcore\Jose\Exception\InvalidJwkException;
use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\JWK;

/**
 * Class RSAKey
 * @package Swiftcore\Jose\Key
 */
final class RSAKey extends JWK
{
    /**
     * @var string
     */
    private $n;
    /**
     * @var string
     */
    private $e;
    /**
     * @var string
     */
    private $d;
    /**
     * @var string
     */
    private $p;
    /**
     * @var string
     */
    private $q;
    /**
     * @var string
     */
    private $dp;
    /**
     * @var string
     */
    private $dq;
    /**
     * @var string
     */
    private $qi;
    /**
     * @var string
     */
    private $oth;

    public $res;

    const RSA_OPENSSL_JOSE_MAPPING = [
        'n' => 'n',
        'd' => 'd',
        'e' => 'e',
        'p' => 'p',
        'q' => 'q',
        'dmp1' => 'dp',
        'dmq1' => 'dq',
        'iqmp' => 'qi',
    ];

    /**
     * RSAKey constructor.
     *
     * Following array structure may be accepted for the first parameter:
     * *Plain key (each member must be Base64url encoded - Section 5 of RFC4648):*
     * ```php
     * [
     *     'n'   => '0vx7agoebGcQSuuPiLJXZptN9nndrQmbXEps2aiAFbWhM78LhWx4'.
     *              'cbbfAAtVT86zwu1RK7aPFFxuhDR1L6tSoc_BJECPebWKRXjBZCiFV4n3oknjhMst'.
     *              'n64tZ_2W-5JsGY4Hc5n9yBXArwl93lqt7_RN5w6Cf0h4QyQ5v-65YGjQR0_FDW2Q'.
     *              'vzqY368QQMicAtaSqzs8KJZgnYb9c7d0zgdAZHzu6qMQvRL5hajrn1n91CbOpbIS'.
     *              'D08qNLyrdkt-bFTWhAI4vMQFh6WeZu0fM4lFd2NcRwr3XPksINHaQ-G_xBniIqbw'.
     *              '0Ls1jF44-csFCur-kEgU8awapJzKnqDKgw',
     *     'e'   => '4AQAB',
     *     'd'   => '870X4cTteJY_gn4FYPsXB8rdXix5vwsg1FLN5E3EaG6RJoVH-HLLKD9'.
     *              'M7dx5oo7GURknchnrRweUkC7hT5fJLM0WbFAKNLWY2vv7B6NqXSzUvxT0_YSfqij'.
     *              'wp3RTzlBaCxWp4doFk5N2o8Gy_nHNKroADIkJ46pRUohsXywbReAdYaMwFs9tv8d'.
     *              '_cPVY3i07a3t8MN6TNwm0dSawm9v47UiCl3Sk5ZiG7xojPLu4sbg1U2jx4IBTNBz'.
     *              'nbJSzFHK66jT8bgkuqsk0GjskDJk19Z4qwjwbsnn4j2WBii3RL-Us2lGVkY8fkFz'.
     *              'me1z0HbIkfz0Y6mqnOYtqc0X4jfcKoAC8Q',
     *     'p'   => '83i-7IvMGXoMXCskv73TKr8637FiO7Z27zv8oj6pbWUQyLPQBQxtPV'.
     *              'nwD20R-60eTDmD2ujnMt5PoqMrm8RfmNhVWDtjjMmCMjOpSXicFHj7XOuVIYQyqV'.
     *              'WlWEh6dN36GVZYk93N8Bc9vY41xy8B9RzzOGVQzXvNEvn7O0nVbfs',
     *     'q'   => '3dfOR9cuYq-0S-mkFLzgItgMEfFzB2q3hWehMuG0oCuqnb3vobLyum'.
     *              'qjVZQO1dIrdwgTnCdpYzBcOfW5r370AFXjiWft_NGEiovonizhKpo9VVS78TzFgx'.
     *              'kIdrecRezsZ-1kYd_s1qDbxtkDEgfAITAG9LUnADun4vIcb6yelxk',
     *     'dp'  => 'G4sPXkc6Ya9y8oJW9_ILj4xuppu0lzi_H7VTkS8xj5SdX3coE0oim'.
     *              'YwxIi2emTAue0UOa5dpgFGyBJ4c8tQ2VF402XRugKDTP8akYhFo5tAA77Qe_Nmtu'.
     *              'YZc3C3m3I24G2GvR5sSDxUyAN2zq8Lfn9EUms6rY3Ob8YeiKkTiBj0',
     *     'dq'   => 's9lAH9fggBsoFR8Oac2R_E2gw282rT2kGOAhvIllETE1efrA6huUU'.
     *              'vMfBcMpn8lqeW6vzznYY5SSQF7pMdC_agI3nG8Ibp1BUb0JUiraRNqUfLhcQb_d9'.
     *              'GF4Dh7e74WbRsobRonujTYN1xCaP6TO61jvWrX-L18txXw494Q_cgk',
     *     'qi'  => 'GyM_p6JrXySiz1toFgKbWV-JdI3jQ4ypu9rbMWx3rQJBfmt0FoYzg'.
     *              'UIZEVFEcOqwemRN81zoDAaa-Bk0KWNGDjJHZDdDmFhW3AN7lI-puxk_mHZGJ11rx'.
     *              'yR8O55XLSe3SPmRfKwZI6yU24ZxvQKFYItdldUKGzO6Ia6zTKhAVRU',
     *     'oth' => '...',
     * ]
     * ```
     * *PKCS#1 formatted key content (each member must be Base64 encoded -
     * Section 4 of RFC4648 -- not base64url-encoded)
     * Associative array:*
     * ```php
     * [
     *     'content'    => 'MIIDQjCCAiqgAwIBAgIGATz/FuLiMA0GCSqGSIb3DQEBBQUAMGIxCzAJB'.
     *                     'gNVBAYTAlVTMQswCQYDVQQIEwJDTzEPMA0GA1UEBxMGRGVudmVyMRwwGgYD'.
     *                     'VQQKExNQaW5nIElkZW50aXR5IENvcnAuMRcwFQYDVQQDEw5CcmlhbiBDYW1'.
     *                     'wYmVsbDAeFw0xMzAyMjEyMzI5MTVaFw0xODA4MTQyMjI5MTVaMGIxCzAJBg'.
     *                     'NVBAYTAlVTMQswCQYDVQQIEwJDTzEPMA0GA1UEBxMGRGVudmVyMRwwGgYDV'.
     *                     'QQKExNQaW5nIElkZW50aXR5IENvcnAuMRcwFQYDVQQDEw5CcmlhbiBDYW1w'.
     *                     'YmVsbDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL64zn8/QnH'.
     *                     'YMeZ0LncoXaEde1fiLm1jHjmQsF/449IYALM9if6amFtPDy2yvz3YlRij66'.
     *                     's5gyLCyO7ANuVRJx1NbgizcAblIgjtdf/u3WG7K+IiZhtELto/A7Fck9Ws6'.
     *                     'SQvzRvOE8uSirYbgmj6He4iO8NCyvaK0jIQRMMGQwsU1quGmFgHIXPLfnpn'.
     *                     'fajr1rVTAwtgV5LEZ4Iel+W1GC8ugMhyr4/p1MtcIM42EA8BzE6ZQqC7VPq'.
     *                     'PvEjZ2dbZkaBhPbiZAS3YeYBRDWm1p1OZtWamT3cEvqqPpnjL1XyW+oyVVk'.
     *                     'aZdklLQp2Btgt9qr21m42f4wTw+Xrp6rCKNb0CAwEAATANBgkqhkiG9w0BA'.
     *                     'QUFAAOCAQEAh8zGlfSlcI0o3rYDPBB07aXNswb4ECNIKG0CETTUxmXl9KUL'.
     *                     '+9gGlqCz5iWLOgWsnrcKcY0vXPG9J1r9AqBNTqNgHq2G03X09266X5CpOe1'.
     *                     'zFo+Owb1zxtp3PehFdfQJ610CDLEaS9V9Rqp17hCyybEpOGVwe8fnk+fbEL'.
     *                     '2Bo3UPGrpsHzUoaGpDftmWssZkhpBJKVMJyf/RuP2SmmaIzmnw9JiSlYhzo'.
     *                     '4tpzd5rFXhjRbg4zW9C+2qok+2+qDM1iJ684gPHMIY8aLWrdgQTxkumGmTq'.
     *                     'gawR+N5MDtdPTEQ0XfIBc2cJEUyMTY5MPvACWpkA6SdS4xSvdXK3IVfOWA==',
     *     'passphrase' => '...',
     * ]
     * ```
     * Sequential array is accepted too:*
     * ```php
     * ['...', '...']
     * ```
     *
     * @param array|string|null $key
     */
    public function __construct($key = null)
    {
        $type = gettype($key);
        switch ($type) {
            case 'NULL':
                return $this;
            case 'array':
                $key = array_values($key);
                break;
            case 'string':
                $key = func_get_args();
                break;
            default:
                throw new InvalidRSAKeyArgumentException(["Parameter type: {$type} is not supported."]);
                break;
        }

        $count = count($key);
        $pem = $key[0];
        if (file_exists($pem)) {
            $pem = file_get_contents($pem);
        }
        $passphrase = null;
        if ($count > 1) {
            $passphrase = $key[1];
        }
        $this->loadPEM($pem, $passphrase);

        return $this;
    }

    /**
     * @param $pem
     * @param $passphrase
     * @return $this
     */
    public function loadPEM($pem, $passphrase)
    {
        $res = openssl_pkey_get_private($pem, $passphrase);
        if (false === $res) {
            $res = openssl_pkey_get_public($pem);
        }

        if (false === $res) {
            throw new InvalidRSAKeyArgumentException(["RSA key seems to be invalid."], 'Failed to load RSA Key');
        }
        $details = openssl_pkey_get_details($res);
        foreach ($details['rsa'] as $opensslName => $value) {
            $joseName = self::RSA_OPENSSL_JOSE_MAPPING[$opensslName];
            $this->$joseName = $value;
        }

        $this->res = $res;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublicKey()
    {
        return !$this->isPrivateKey();
    }

    /**
     * @return bool
     */
    public function isPrivateKey()
    {
        return !empty($this->d) && !empty($this->n);
    }
}
