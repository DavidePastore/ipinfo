ipinfo
======

A wrapper around the ipinfo.io services.


Install
-------

You can install the library using composer. Add these lines in your composer.json:




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
//Get all properties
$host = $ipInfo->getFullIpDetails("8.8.8.8");

//Read all properties
$city = $host->getCity();
$country = $host->getCountry();
$hostname = $host->getHostname();
$ip = $host->getIp();
$loc = $host->getLoc();
$org = $host->getOrg();
$phone = $host->getPhone();
$region = $host->getRegion();

//Get only a single property
$city = $ipInfo->getSpecificField(DavidePastore\Ipinfo\Ipinfo::CITY);
```

### Read details about your ip

You can read all the properties from your ip.

```php
//Get all properties
$host = $ipInfo->getYourOwnIpSpecificField("8.8.8.8");

//Read all properties
$city = $host->getCity();
$country = $host->getCountry();
$hostname = $host->getHostname();
$ip = $host->getIp();
$loc = $host->getLoc();
$org = $host->getOrg();
$phone = $host->getPhone();
$region = $host->getRegion();

//Get only a single property
$city = $ipInfo->getSpecificField(DavidePastore\Ipinfo\Ipinfo::CITY);
```

### Read only a field

There are different constants that you could use to read specific field value from an `Ipinfo` instance:

```php
IpInfo::IP;
IpInfo::IP;
IpInfo::IP;
IpInfo::IP;
```
