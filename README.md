codeigniter-currency-converter V 1.3.2
==============================

[![Code Quality](https://scrutinizer-ci.com/g/alessandrominoccheri/codeigniter-currency-converter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alessandrominoccheri/codeigniter-currency-converter/badges/quality-score.png?b=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alessandrominoccheri/codeigniter-currency-converter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alessandrominoccheri/codeigniter-currency-converter/badges/coverage.png?b=master)
[![Latest Stable Version](https://poser.pugx.org/alessandrominoccheri/codeigniter-currency-converter/v/stable.svg)](https://packagist.org/packages/alessandrominoccheri/codeigniter-currency-converter)
[![License](https://poser.pugx.org/alessandrominoccheri/codeigniter-currency-converter/license.svg)](https://packagist.org/packages/alessandrominoccheri/codeigniter-currency-converter)
[![Build Status](https://api.travis-ci.org/AlessandroMinoccheri/codeigniter-currency-converter.png)](https://travis-ci.org/AlessandroMinoccheri/codeigniter-currency-converter)
[![Total Downloads](https://poser.pugx.org/alessandrominoccheri/codeigniter-currency-converter/d/total.png)](https://packagist.org/packages/alessandrominoccheri/codeigniter-currency-converter)

A Codeigniter library to convert your price from a currency to another

---

## Background

Is very frequently that inside your sites you need to convert your price from a currency to another.
This library convert your price in every currency of the world.

It works with fixer.io api and store currency rates inside the site database if you want.
User can configure in hour the time of currency rates update, if the user doesn't want to use database, rates are updated every time with the current conversion.

If you have set to use database, for example, user sets to update currency rates every hour, this library get the currency conversion from fixer.io the first time, store it inside the database and for the next hour it takes conversion rates from the database if exist.
In this way reduce the request time to convert and every hour currency rates are updated.

If you haven't set to use database, instead, every time you call the library it makes a request to fixer.io api and gets the actual conversion rate. This solution is great if you have not a lot of request. Instead, if you have a lot of conversion request is better to use the database configuration.


---


## Requirements

* CodeIgniter 2.x or 3.x
* PHP 5.3 or gretaer

---

# Installation

With composer you can install this package:

```
composer require alessandrominoccheri/codeigniter-currency-converter
```

Or Drag and drop the application/libraries/CurrencyConverter.php file into your application directories. Load it from your application/config/autoload.php using: 

```
$autoload['libraries'] = array('database', 'CurrencyConverter');
```

Or inside your model you can use:
 
```
$this->CurrencyConverter = new CurrencyConverter();
```

It's important to have a valid database connection because the library saves conversion inside it if you want to use it.

---

# Usage

To convert your price you can make a request like this from your model / controller:

```
$result = $this->CurrencyConverter->convert('GBP', 'EUR', '2000.00', true, 1);
```

To get a list of currency code you can check here:

[List of available currency code](http://www.xe.com/iso4217.php )

---

# Params

The function declaration to retrieve your converted price is:

```
function convert($from_Currency, $to_Currency, $amount, $save_into_db = 1, $hour_difference = 1)
```

* fromCurrency: is the actual price currency (Example: EUR, GBP)
* toCurrency: is the currency that you want to convert your price (Example: EUR, GBP)
* amount: is the price to convert (Example: 200, 20)
* saveIntoDb: is the variable that configure to use the database or not, if not hour_difference params is escaped
* hourDifference: is the hour difference to update your currency conversion. For example if you have set to update currency rates every hour, this library get the currency conversion from fixer.io the first time, store it inside the database and for the next hour it takes conversion rates from the database if exist.

---


# Api KEY

To use this library you need to add your api key inside the configuration.
to get your api key go to this site and generate it.

[Generate your api key](https://www.currencyconverterapi.com/)

---
## License

The MIT License (MIT)

Copyright (c) 2014 Alessandro Minoccheri

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
