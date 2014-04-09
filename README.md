codeigniter-currency-converter
==============================

A Codeigniter library to convert your price from a currency to another

---

##Background

Is very frequently that inside your sites you need to convert your price from a currency to another.
This library convert your price in every currency of the world.

It works with yahoo finance api and store currency rates inside the site database.
User can configure in hour the time of currency rates update.

For example ifuser sets to update currency rates every hour, this library get the currency conversion from yahoo finance the first time, store it inside the database and for the next hour it takes conversion rates from the database if exist.
In this way reduce the request time to convert and every hour currency rates are updated.

The list of available currency is

---

##Requirements

* CodeIgniter 2.1.0
* PHP5.2 or gretaer

---

#Installation
Drag and drop the application/libraries/CurrencyConverter.php file into your application's directories. Load it from your application/config/autoload.php using: 
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