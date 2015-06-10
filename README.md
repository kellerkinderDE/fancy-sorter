# Fancy Sorter

Fancy Sorter is a small library that offers several sorters for specific use classes often known and seen in ecommerce projects that offer clothing products.

That is, sorting sizes like:
* jeans (32/30, 34W/30L, …)
* clothing (XS, S, M, L, XL, XXL, 3XL, …)
* numeric (32, 34, 36, 50, 52, 96, 128, …)

As well as the possibilty to chained needed sorters together, so the best fit is automatically used.

## Installing Fancy Sorter

The recommended way to install Fancy Sorter is through [Composer](http://getcomposer.org/):

```
composer require kellerkinder/fancy-sorter
```

## Using Fancy Sorter

```php
$sorter = new JeansSizeSorter();
$result = $sorter->sort(['30/32', '32 / 34', '32/ 30', '30W/30L', '32W / 32L']);
// => ['30W/30L', '30/32', '32/ 30', '32W / 32L', '32 / 34']
```