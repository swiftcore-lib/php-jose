# Swiftcore Javascript Object Signing and Encryption (JOSE) PHP Library Overview

[![Build Status](https://travis-ci.org/swiftcore-lib/php-jose.svg?branch=master)](https://travis-ci.org/swiftcore-lib/php-jose) 
[![Coverage Status](https://coveralls.io/repos/github/swiftcore-lib/php-jose/badge.svg?branch=master)](https://coveralls.io/github/swiftcore-lib/php-jose?branch=master)
[![PHP 7 ready](http://php7ready.timesplinter.ch/swiftcore-lib/php-jose/master/badge.svg)](https://travis-ci.org/swiftcore-lib/php-jose)
[![HHVM Status](http://hhvm.h4cc.de/badge/swiftcore-lib/php-jose.svg?style=flat)](http://hhvm.h4cc.de/package/swiftcore-lib/php-jose)

This library provides the pure PHP implementation of [Javascript Object Signing and Encryption](https://datatracker.ietf.org/wg/jose/documents/) that provides tremendous performance.

## Features

### Signature Algorithms Supported

* HS256, HS384, HS512
* RS256, RS384, RS512
* ES256, ES384, ES512

Not implemented now but coming soon:

* PS256, PS384, PS512
* none

Currently implemented:

* [x] [RFC 7515 - JSON Web Signature (JWS)](https://datatracker.ietf.org/doc/rfc7515/?include_text=1).
* [x] [RFC 7518 - JSON Web Algorithms (JWA)](https://datatracker.ietf.org/doc/rfc7518/?include_text=1)

To be implemented:

* [ ] [RFC 7516 - JSON Web Encryption (JWE)](https://datatracker.ietf.org/doc/rfc7516/?include_text=1)
* [ ] [RFC 7638 - JSON Web Key (JWK) Thumbprint](https://datatracker.ietf.org/doc/rfc7638/?include_text=1)



