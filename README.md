ipinfo
======

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![PSR2 Conformance][ico-styleci]][link-styleci]


A wrapper around the [ipinfo.io](http://ipinfo.io/) services.


Install
-------

You can install the library using [composer](https://getcomposer.org/):

```sh
$ composer require davidepastore/ipinfo
```

How to use
----------

### Settings

#### Token

You can set your token when you instantiate the object but it's not mandatory.

```php
$ipInfo = new DavidePastore\Ipinfo\Ipinfo(array(
	"token" => "your_api_key"
));
```

#### cURL options

The cURL options to use while trying to connect when you instantiate the object:

```php
$ipInfo = new DavidePastore\Ipinfo\Ipinfo(array(
	"curlOptions" => array(
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_TIMEOUT => 2,
            CURLOPT_CAINFO => __DIR__ . "/cacert.pem"
    )
));
```

### Read details about the given ip

You can read all the properties from the given ip.

```php
//Get all the properties
$host = $ipInfo->getFullIpDetails("8.8.8.8");

//Get only a single property (this could save bandwidth)
$city = $ipInfo->getSpecificField("8.8.8.8", DavidePastore\Ipinfo\Ipinfo::CITY);
```

### Read details about your ip

You can read all the properties from your ip.

```php
//Get all the properties
$host = $ipInfo->getYourOwnIpDetails();

//Get only a single property (this could save bandwidth)
$city = $ipInfo->getYourOwnIpSpecificField(DavidePastore\Ipinfo\Ipinfo::CITY);
```

### Get info from the host

After obtaining the `Host` instance you can read all the properties or each of them individually.

```php
//Read all the properties
$city = $host->getCity();
$country = $host->getCountry();
$hostname = $host->getHostname();
$ip = $host->getIp();
$loc = $host->getLoc();
$org = $host->getOrg();
$phone = $host->getPhone();
$postal = $host->getPostal();
$region = $host->getRegion();

//Get the associative array with all the properties
$properties = $host->getProperties();
```

### Read only a field

There are different constants that you could use to read specific field value from an `Ipinfo` instance using the `getSpecificField()` and `getYourOwnIpSpecificField()` methods:

```php
IpInfo::IP; //For the ip address
IpInfo::HOSTNAME; //For the hostname
IpInfo::LOC; //For the loc
IpInfo::ORG; //For the org
IpInfo::CITY; //For the city
IpInfo::REGION; //For the region
IpInfo::COUNTRY; //For the country
IpInfo::PHONE; //For the phone
IpInfo::POSTAL; //For the postal
IpInfo::GEO; //For the geo info. See the paragraph below for more info
```

### Read only the Geo data (which is faster)

By using the `getIpGeoDetails()` method you will get less fields. This call tends to be faster than `getFullIpDetails()` so use this call in case you only need the following fields:

```php
IpInfo::IP; //For the ip address
IpInfo::CITY; //For the city
IpInfo::REGION; //For the region
IpInfo::COUNTRY; //For the country
IpInfo::PHONE; //For the phone
IpInfo::POSTAL; //For the postal
```

These fields will be empty:
```php
IpInfo::HOSTNAME; //For the hostname
IpInfo::LOC; //For the loc
IpInfo::ORG; //For the org
```

### Error Handling

You can handle all the types of IpInfo exceptions by catching the `IpInfoExceptionException`:

```php
use DavidePastore\Ipinfo\Exception\IpInfoExceptionException;

try {
    $host = $ipInfo->getFullIpDetails("8.8.8.8");
} catch (IpInfoExceptionException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
```

#### Invalid Token Exception

It could happen that the token you are using to make the API call is not valid. You can handle it by catching the `InvalidTokenException`:

```php
use DavidePastore\Ipinfo\Exception\InvalidTokenException;

try {
    $host = $ipInfo->getFullIpDetails("8.8.8.8");
} catch (InvalidTokenException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
```

#### Rate Limit Exceed Exception

It could happen that your API call exceeds the rate limit. You can handle it by catching the `RateLimitExceedException`:

```php
use DavidePastore\Ipinfo\Exception\RateLimitExceedException;

try {
    $host = $ipInfo->getFullIpDetails("8.8.8.8");
} catch (RateLimitExceedException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
```

#### Wrong Ip Exception

It could happen that your API call is trying to obtain info about a wrong ip. You can handle it by catching the `WrongIpException`:

```php
use DavidePastore\Ipinfo\Exception\WrongIpException;

try {
    $host = $ipInfo->getFullIpDetails("qwerty");
} catch (WrongIpException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
```

Issues
-------

If you have issues, just open one [here](https://github.com/DavidePastore/ipinfo/issues).


[ico-version]: https://img.shields.io/packagist/v/DavidePastore/ipinfo.svg?style=flat-square
[ico-travis]: https://travis-ci.org/DavidePastore/ipinfo.svg?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DavidePastore/ipinfo.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/davidepastore/ipinfo.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DavidePastore/ipinfo.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/24985619/shield

[link-packagist]: https://packagist.org/packages/DavidePastore/ipinfo
[link-travis]: https://travis-ci.org/DavidePastore/ipinfo
[link-scrutinizer]: https://scrutinizer-ci.com/g/DavidePastore/ipinfo/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DavidePastore/ipinfo
[link-downloads]: https://packagist.org/packages/DavidePastore/ipinfo
[link-styleci]: https://styleci.io/repos/24985619/
