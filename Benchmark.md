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

### SignatureBenchmark of Tiny payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.268 ms	|	1268 ms	|	1000
	RS384		|	1.283 ms	|	1283 ms	|	1000
	RS512		|	1.254 ms	|	1254 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.029 ms	|	29 ms	|	1000
	RS384		|	0.032 ms	|	32 ms	|	1000
	RS512		|	0.033 ms	|	33 ms	|	1000


### SignatureBenchmark of 3mb payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	33.352 ms	|	1668 ms	|	50
	RS384		|	32.287 ms	|	1614 ms	|	50
	RS512		|	33.378 ms	|	1669 ms	|	50


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	31.998 ms	|	1600 ms	|	50
	RS384		|	27.608 ms	|	1380 ms	|	50
	RS512		|	27.591 ms	|	1380 ms	|	50


### SignatureBenchmark of 8mb payload

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	77.348 ms	|	1547 ms	|	20
	RS384		|	77.428 ms	|	1549 ms	|	20
	RS512		|	76.821 ms	|	1536 ms	|	20


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	76.103 ms	|	1522 ms	|	20
	RS384		|	65.706 ms	|	1314 ms	|	20
	RS512		|	66.239 ms	|	1325 ms	|	20

