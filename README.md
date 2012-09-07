#AdZerk-PHP-Client

####PHP Client for AdZerk API

Usage Tips, Advertisers as example, consistent behavior for all endpoints:

List Advertisers

```php
$adzerk->advertiser(); // no arguments
```

Create Advertiser

```php
$adzerk->advertiser(null, array()); // arg1: null, arg2: array of data (plain-text associative PHP array)
```

Update Advertiser

```php
$adzerk->advertiser($AdvertiserId, array()); // arg1: (int) Advertiser ID, arg2: array of data (plain-text associative PHP array)
```

All responses are returned by default, as an associative PHP array