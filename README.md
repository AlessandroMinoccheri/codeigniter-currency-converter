codeigniter-currency-converter
==============================

A Codeigniter library to convert your price from a currency to another

---

##Background

Is very frequently that inside your sites you need to convert your price from a currency to another.
This library convert your price in every currency of the world.

It works with yahoo finance api and store currency rates inside the site database.
User can configure in hour the time of currency rates update.

For example if a user sets to update currency rates every hour, this library get the currency conversion from yahoo finance the first time, store it inside the database and for the next hour it takes conversion rates from the database if exist.
In this way reduce the request time to convert and every hour currency rates are updated.

---

##Requirements

* CodeIgniter 2.x
* PHP 5.2 or gretaer

---

#Installation
Drag and drop the application/libraries/CurrencyConverter.php file into your application directories. Load it from your application/config/autoload.php using: 
```
$autoload['libraries'] = array('database', 'CurrencyConverter');
```

Or inside your model you can use: 
```
$this->CurrencyConverter = new CurrencyConverter();
```

It's important to have a valid database connection because the library saves conversion inside it.

---

#Usage

To convert your price you can make a request like this from your model / controller:
```
$result = $this->CurrencyConverter->convert('GBP', 'EUR', '2000,00', '1');
```

---
#Params

The function declaration to retrieve your converted price is:
```
function convert($from_Currency,$to_Currency,$amount, $hour_difference = 1)
```

* from_Currency: is the actual price currency (Example: EUR, GBP)
* to_Currency: is the currency that you want to convert your price (Example: EUR, GBP)
* amount: is the price to convert (Example: 200,20)
* hour_difference: is the hour difference to update your currency conversion. For example if you have set to update currency rates every hour, this library get the currency conversion from yahoo finance the first time, store it inside the database and for the next hour it takes conversion rates from the database if exist.

---

---
##License

The MIT License (MIT)

Copyright (c) 2014 Alessandro Minoccheri

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
