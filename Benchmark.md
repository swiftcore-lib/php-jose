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

### Signature (Tiny)

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.252 ms	|	1252 ms	|	1000
	RS384		|	1.249 ms	|	1249 ms	|	1000
	RS512		|	1.236 ms	|	1236 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.029 ms	|	29 ms	|	1000
	RS384		|	0.029 ms	|	29 ms	|	1000
	RS512		|	0.033 ms	|	33 ms	|	1000


### Signature (3mb)

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	32.634 ms	|	1632 ms	|	50
	RS384		|	32.562 ms	|	1628 ms	|	50
	RS512		|	32.535 ms	|	1627 ms	|	50


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	30.943 ms	|	1547 ms	|	50
	RS384		|	26.596 ms	|	1330 ms	|	50
	RS512		|	26.834 ms	|	1342 ms	|	50


### Signature (8mb)

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	76.991 ms	|	1540 ms	|	20
	RS384		|	77.723 ms	|	1554 ms	|	20
	RS512		|	78.018 ms	|	1560 ms	|	20


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	77.193 ms	|	1544 ms	|	20
	RS384		|	66.432 ms	|	1329 ms	|	20
	RS512		|	65.116 ms	|	1302 ms	|	20

