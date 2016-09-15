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
```


## Result

### Signature Benchmark of 1KB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.264 ms	|	1264 ms	|	1000
	RS384		|	1.237 ms	|	1237 ms	|	1000
	RS512		|	1.243 ms	|	1243 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.043 ms	|	43 ms	|	1000
	RS384		|	0.038 ms	|	38 ms	|	1000
	RS512		|	0.038 ms	|	38 ms	|	1000


### Signature Benchmark of 100KB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.978 ms	|	1978 ms	|	1000
	RS384		|	1.951 ms	|	1951 ms	|	1000
	RS512		|	1.905 ms	|	1905 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.717 ms	|	717 ms	|	1000
	RS384		|	0.585 ms	|	585 ms	|	1000
	RS512		|	0.631 ms	|	631 ms	|	1000


### Signature Benchmark of 2MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	22.913 ms	|	1146 ms	|	50
	RS384		|	21.301 ms	|	1065 ms	|	50
	RS512		|	21.508 ms	|	1075 ms	|	50


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	20.509 ms	|	1025 ms	|	50
	RS384		|	16.821 ms	|	841 ms	|	50
	RS512		|	16.598 ms	|	830 ms	|	50


### Signature Benchmark of 10MB payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	100.713 ms	|	2014 ms	|	20
	RS384		|	99.986 ms	|	2000 ms	|	20
	RS512		|	99.874 ms	|	1997 ms	|	20


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	98.936 ms	|	1979 ms	|	20
	RS384		|	84.758 ms	|	1695 ms	|	20
	RS512		|	84.079 ms	|	1682 ms	|	20
