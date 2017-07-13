<?php

use DavidePastore\Ipinfo\Ipinfo;
use DavidePastore\Ipinfo\Host;

/**
 * Test for the Ipinfo class.
 *
 * @author davidepastore
 */
class IpinfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test full ip details.
     */
    public function testGetFullIpDetails()
    {
        $ipinfo = new Ipinfo();
        $expected = new Host(array(
            'city' => 'Mountain View',
            'country' => 'US',
            'hostname' => 'google-public-dns-a.google.com',
            'ip' => '8.8.8.8',
            'loc' => '37.3860,-122.0840',
            'org' => 'AS15169 Google Inc.',
            'phone' => '650',
            'postal' => '94035',
            'region' => 'California',
        ));
        $actual = $ipinfo->getFullIpDetails('8.8.8.8');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test all the get method of Host.
     */
    public function testAllGet()
    {
        $ipinfo = new Ipinfo();
        $expectedCity = 'Mountain View';
        $expectedCountry = 'US';
        $expectedHostname = 'google-public-dns-a.google.com';
        $expectedIp = '8.8.8.8';
        $expectedLoc = '37.3860,-122.0840';
        $expectedOrg = 'AS15169 Google Inc.';
        $expectedPhone = '650';
        $expectedPostal = '94035';
        $expectedRegion = 'California';
        $expected = array(
          'city' => $expectedCity,
          'country' => $expectedCountry,
          'hostname' => $expectedHostname,
          'ip' => $expectedIp,
          'loc' => $expectedLoc,
          'org' => $expectedOrg,
          'phone' => $expectedPhone,
          'postal' => $expectedPostal,
          'region' => $expectedRegion,
        );
        $actual = $ipinfo->getFullIpDetails('8.8.8.8');

        $this->assertEquals($expectedCity, $actual->getCity());
        $this->assertEquals($expectedCountry, $actual->getCountry());
        $this->assertEquals($expectedHostname, $actual->getHostname());
        $this->assertEquals($expectedIp, $actual->getIp());
        $this->assertEquals($expectedLoc, $actual->getLoc());
        $this->assertEquals($expectedOrg, $actual->getOrg());
        $this->assertEquals($expectedPhone, $actual->getPhone());
        $this->assertEquals($expectedPostal, $actual->getPostal());
        $this->assertEquals($expectedRegion, $actual->getRegion());
        $this->assertEquals($expected, $actual->getProperties());
    }

    /**
     * Test city field value.
     */
    public function testGetSpecificField()
    {
        $ipinfo = new Ipinfo();
        $expected = 'Mountain View';
        $actual = $ipinfo->getSpecificField('8.8.8.8', Ipinfo::CITY);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test the faster geo call.
     */
    public function testGeoDetails()
    {
        $ipinfo = new Ipinfo();
        $expected = new Host(array(
            'city' => 'Mountain View',
            'country' => 'US',
            'ip' => '8.8.8.8',
            'loc' => '37.3860,-122.0840',
            'postal' => '94035',
            'region' => 'California',

            // Other fields will be empty by default
            'hostname' => '',
            'org' => '',
            'phone' => '650',
        ));
        $actual = $ipinfo->getIpGeoDetails('8.8.8.8');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test your own ip details.
     */
    public function testYourOwnIpDetails()
    {
        $ipinfo = new Ipinfo();
        $host = $ipinfo->getYourOwnIpDetails();
        $actual = $host->getProperties();
        $this->assertArrayHasKey('city', $actual);
        $this->assertArrayHasKey('country', $actual);
        $this->assertArrayHasKey('hostname', $actual);
        $this->assertArrayHasKey('ip', $actual);
        $this->assertArrayHasKey('loc', $actual);
        $this->assertArrayHasKey('org', $actual);
        $this->assertArrayHasKey('phone', $actual);
        $this->assertArrayHasKey('postal', $actual);
        $this->assertArrayHasKey('region', $actual);
    }

    /**
     * Test your own specific field value.
     */
    public function testGetYourOwnIpSpecificField()
    {
        $ipinfo = new Ipinfo();
        $expected = 'Mountain View';
        $actual = $ipinfo->getYourOwnIpSpecificField(Ipinfo::CITY);

        $this->assertTrue(is_string($actual));
    }

    /**
     * Test using a token.
     */
    public function testWithToken()
    {
        $ipinfo = new Ipinfo(array(
          'token' => 'justatest',
        ));
        $expected = 'Mountain View';
        $actual = $ipinfo->getSpecificField('8.8.8.8', Ipinfo::CITY);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test in debug mode.
     */
    public function testDebugMode()
    {
        $ipinfo = new Ipinfo(array(
          'debug' => true,
        ));
        $expected = 'Mountain View';
        $actual = $ipinfo->getSpecificField('8.8.8.8', Ipinfo::CITY);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test a null response.
     */
    public function testNullResponse()
    {
        require_once 'WrongIpinfo.php';
        $ipinfo = new DavidePastore\Ipinfo\WrongIpinfo();

        $expected = new Host(array(
            'city' => '',
            'country' => '',
            'ip' => '',
            'loc' => '',
            'postal' => '',
            'region' => '',

            // Other fields will be empty by default
            'hostname' => '',
            'org' => '',
            'phone' => '',
        ));
        $actual = $ipinfo->getIpGeoDetails('asd/qwerty');

        $this->assertEquals($expected, $actual);
    }
}
