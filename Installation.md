## Prerequisites

### PHP version:

* PHP 5.6.1 or above [![PHP 7 ready](http://php7ready.timesplinter.ch/swiftcore-lib/php-jose/master/badge.svg)](https://travis-ci.org/swiftcore-lib/php-jose)
  * OpenSSL extension
* HHVM 3.6.6 or above (see *HHVM Compatibility* below for more information)

### HHVM Compatibility

This library tests and runs well with hhvm, however, php is still highly recommended because of following reason:

* PHP 7 is now as fast as HHVM (faster than HHVM in most cases)
* HHVM is lack of certain implementation of what PHP has

Followings are not suppored when running with hhvm (rest are working and unit tested):

* ECDSA Signature (ES256/384/512) signing and verifying


## Installation

Preferrable way to install via Composer:

```
composer require swiftcore-lib/php-jose
```