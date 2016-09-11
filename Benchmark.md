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

### Signature

#### Signing

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	1.234 ms	|	1234 ms	|	1000
	RS384		|	1.237 ms	|	1237 ms	|	1000
	RS512		|	1.217 ms	|	1217 ms	|	1000


#### Verifying

	Algorithm	|	Average	|	Total		|	Count
	---------	|	-------	|	-----		|	-----
	RS256		|	0.029 ms	|	878 ms	|	30000
	RS384		|	0.029 ms	|	856 ms	|	30000
	RS512		|	0.029 ms	|	870 ms	|	30000