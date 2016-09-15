# Swiftcore JOSE PHP Library

[![Build Status](https://travis-ci.org/swiftcore-lib/php-jose.svg?branch=master)](https://travis-ci.org/swiftcore-lib/php-jose) 
[![Coverage Status](https://coveralls.io/repos/github/swiftcore-lib/php-jose/badge.svg?branch=master)](https://coveralls.io/github/swiftcore-lib/php-jose?branch=master)
[![PHP 7 ready](http://php7ready.timesplinter.ch/swiftcore-lib/php-jose/master/badge.svg)](https://travis-ci.org/swiftcore-lib/php-jose)

A high performance pure PHP implementation of Javascript Object Signing and Encryption (JOSE).

Refer to [Wiki](https://github.com/swiftcore-lib/php-jose/wiki) page for more information.

## Installation

Preferrable way to install via Composer:

```
composer require swiftcore-lib/php-jose
```

## Notice & Disclaimer

> This library is not yet stable at the moment, API may change without prior notification, please use it at your own risk. 
> 
> [Pull Request](https://github.com/swiftcore-lib/php-jose/pulls) are welcome. Please use [GitHub Issues](https://github.com/swiftcore-lib/php-jose/issues) for any issue / problem encoutered.

## Roadmap

* [x] v0.1 (September 2016):
  * JWS fundamental
  * JWS RSxxx (SHAxxx with RSA)
* [x] v0.2 (September 2016):
  * JWS HSxxx (HMAC SHAxxx)
* [ ] v0.3 (September 2016)
  * JWS ESxxx (SHAxxx with ECDSA)
* [ ] v0.4 (October 2016)
  * JWS PSxxx (SHAxxx with RSA and MGF1)
* [ ] v1.0 (October 2016)
  * Full documentation of JWS
* [ ] v2.1 (December 2016):
  * JWE Implementation
* [ ] And more...


## OpenSSL Command

Keys with EC-curves, generated with X509-format

JWA ES256 (ECDSA using P-256 curve and SHA-256 hash algorithm) would be named curve secp256k1: 
SECG curve over a 256 bit prime field in OpenSSL
Example: 

    openssl ecparam -out es256.pem -name secp256k1 -genkey

JWA ES384 (ECDSA using P-384 curve and SHA-384 hash algorithm) would be named curve secp384r1: 
NIST/SECG curve over a 384 bit prime field in OpenSSL
Example: openssl ecparam -out es384.pem -name secp384r1 -genkey

JWA ES512 (ECDSA using P-521 curve and SHA-512 hash algorithm) would be named curve secp521r1: 
NIST/SECG curve over a 521 bit prime field in OpenSSL
Example: 

    openssl ecparam -out es521.pem -name secp521r1 -genkey

Keys with RSA

RS256 (RSASSA using SHA-256 hash algorithm) 
Example: 

    openssl genrsa -out rs256.pem 256

RS384 (RSASSA using SHA-384 hash algorithm)
Example: 

    openssl genrsa -out rs384.pem 384

RS512 (RSASSA using SHA-512 hash algorithm)
Example: 

    openssl genrsa -out rs512.pem 512

For HMAC keys I would use

    openssl rand -<base64|hex> <256|384|512>

Where length corresponds to which HMAC I am using. I read that the recommendation is to have the keys be the same site at the block size used for hashing.

Example (for HMAC 512) key generation: 

    openssl rand -base64 512
    
To get a base64 encoded key.