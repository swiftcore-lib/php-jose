## Overview

To run benchmark locally, execute following command in `vendor/swiftcore/php-jose/`:

```
composer benchmark
```

## Environment

Following are the result of running on (old) Mac mini (Late 2012) with following configuration:

- Processor: 2.3 GHz Intel Core i7
- Memory: 16 GB 1600 MHz DDR3
- PHP: 7.0.10 (installed by *homebrew*)

```
PHP 7.0.10 (cli) (built: Aug 21 2016 19:14:33) ( NTS )
Copyright (c) 1997-2016 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2016 Zend Technologies
    with Xdebug v2.4.1, Copyright (c) 2002-2016, by Derick Rethans
```

## Result

### Signature Benchmark of Tiny payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.245 ms	|	1245 ms	|	1000
	RS384		|	1.25 ms	|	1250 ms	|	1000
	RS512		|	1.244 ms	|	1244 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.029 ms	|	29 ms	|	1000
	RS384		|	0.028 ms	|	28 ms	|	1000
	RS512		|	0.029 ms	|	29 ms	|	1000


### Signature Benchmark of 2MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	21.209 ms	|	1060 ms	|	50
	RS384		|	21.004 ms	|	1050 ms	|	50
	RS512		|	20.957 ms	|	1048 ms	|	50


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	20.186 ms	|	1009 ms	|	50
	RS384		|	16.666 ms	|	833 ms	|	50
	RS512		|	17.01 ms	|	851 ms	|	50


### Signature Benchmark of 10MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	101.183 ms	|	2024 ms	|	20
	RS384		|	100.285 ms	|	2006 ms	|	20
	RS512		|	100.457 ms	|	2009 ms	|	20


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	99.031 ms	|	1981 ms	|	20
	RS384		|	84.639 ms	|	1693 ms	|	20
	RS512		|	86.08 ms	|	1722 ms	|	20



