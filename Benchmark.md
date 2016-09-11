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

### Signature Benchmark of 1KB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.302 ms	|	1302 ms	|	1000
	RS384		|	1.27 ms	|	1270 ms	|	1000
	RS512		|	1.327 ms	|	1327 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.037 ms	|	37 ms	|	1000
	RS384		|	0.037 ms	|	37 ms	|	1000
	RS512		|	0.037 ms	|	37 ms	|	1000


### Signature Benchmark of 100KB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.998 ms	|	1998 ms	|	1000
	RS384		|	1.939 ms	|	1939 ms	|	1000
	RS512		|	1.924 ms	|	1924 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.717 ms	|	717 ms	|	1000
	RS384		|	0.564 ms	|	564 ms	|	1000
	RS512		|	0.577 ms	|	577 ms	|	1000


### Signature Benchmark of 2MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	20.965 ms	|	1048 ms	|	50
	RS384		|	21.436 ms	|	1072 ms	|	50
	RS512		|	21.261 ms	|	1063 ms	|	50


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	19.375 ms	|	969 ms	|	50
	RS384		|	16.402 ms	|	820 ms	|	50
	RS512		|	16.775 ms	|	839 ms	|	50


### Signature Benchmark of 10MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	99.369 ms	|	1987 ms	|	20
	RS384		|	99.982 ms	|	2000 ms	|	20
	RS512		|	100.462 ms	|	2009 ms	|	20


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	98.609 ms	|	1972 ms	|	20
	RS384		|	85.297 ms	|	1706 ms	|	20
	RS512		|	83.874 ms	|	1677 ms	|	20





