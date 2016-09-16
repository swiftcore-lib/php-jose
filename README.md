# Swiftcore JOSE PHP Library

[![Build Status](https://travis-ci.org/swiftcore-lib/php-jose.svg?branch=master)](https://travis-ci.org/swiftcore-lib/php-jose) 
[![Coverage Status](https://coveralls.io/repos/github/swiftcore-lib/php-jose/badge.svg?branch=master)](https://coveralls.io/github/swiftcore-lib/php-jose?branch=master)
[![PHP 7 ready](http://php7ready.timesplinter.ch/swiftcore-lib/php-jose/master/badge.svg)](https://travis-ci.org/swiftcore-lib/php-jose)
[![HHVM Status](http://hhvm.h4cc.de/badge/swiftcore-lib/php-jose.svg?style=flat)](http://hhvm.h4cc.de/package/swiftcore-lib/php-jose)

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


## Known Issue

* `mbstring.func_overload` may result `strlen()` unexpected result
 
 