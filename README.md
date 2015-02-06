ipinfo
======

[![Build Status](https://travis-ci.org/DavidePastore/ipinfo.svg?branch=master)](https://travis-ci.org/DavidePastore/ipinfo)


A wrapper around the [ipinfo.io](http://ipinfo.io/) services.


Install
-------

You can install the library using [composer](https://getcomposer.org/). Add these lines in your composer.json:

```json
"require" : {
	"davidepastore/ipinfo" : "v0.1.1"
}
```

How to use
----------

### Set your token

You can set your token when you instantiate the object but it's not mandatory.

```php
$ipInfo = new DavidePastore\Ipinfo\Ipinfo(array(
	"token" => "your_api_key"
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
$host = $ipInfo->getYourOwnIpSpecificField();

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

Issues
-------

If you have issues, just open one [here](https://github.com/DavidePastore/ipinfo/issues).
