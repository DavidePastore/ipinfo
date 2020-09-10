<?php

use DavidePastore\Ipinfo\Ipinfo;
use DavidePastore\Ipinfo\Host;
use PHPUnit\Framework\TestCase;

/**
 * Test for the Ipinfo class.
 *
 * @author davidepastore
 */
class IpinfoTest extends TestCase
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
            'hostname' => 'dns.google',
            'ip' => '8.8.8.8',
            'loc' => '37.4056,-122.0775',
            'org' => 'AS15169 Google LLC',
            'phone' => '',
            'postal' => '94043',
            'region' => 'California',
            'timezone' => 'America/Los_Angeles',
            'readme' => 'https://ipinfo.io/missingauth'
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
        $expectedHostname = 'dns.google';
        $expectedIp = '8.8.8.8';
        $expectedLoc = '37.4056,-122.0775';
        $expectedOrg = 'AS15169 Google LLC';
        $expectedPhone = '';
        $expectedPostal = '94043';
        $expectedRegion = 'California';
        $expectedTimezone = 'America/Los_Angeles';
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
          'timezone' => $expectedTimezone,
          'readme' => 'https://ipinfo.io/missingauth'
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
            'loc' => '37.4056,-122.0775',
            'postal' => '94043',
            'region' => 'California',
            'timezone' => 'America/Los_Angeles',
            'readme' => 'https://ipinfo.io/missingauth',

            // Other fields will be empty by default
            'hostname' => '',
            'org' => '',
            'phone' => ''
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
        $actual = $ipinfo->getYourOwnIpSpecificField(Ipinfo::COUNTRY);

        $this->assertTrue(is_string($actual));
    }

    /**
     * Test using a token.
     */
    public function testWithToken()
    {
        $ipinfo = new Ipinfo(array(
          'token' => ' ',
        ));
        $expected = 'Mountain View';
        $actual = $ipinfo->getSpecificField('8.8.8.8', Ipinfo::CITY);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test with a wrong token.
     * @expectedException DavidePastore\Ipinfo\Exception\InvalidTokenException
     */
    public function testWithWrongToken()
    {
        $ipinfo = new Ipinfo(array(
          'token' => 'wrong-token',
        ));
        $ipinfo->getSpecificField('8.8.8.8', Ipinfo::CITY);
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
     * Test a rate limit error.
     * @expectedException DavidePastore\Ipinfo\Exception\RateLimitExceedException
     */
    public function testRateLimitExceed()
    {
        require_once 'RateLimitExceedIpinfo.php';
        $ipinfo = new DavidePastore\Ipinfo\RateLimitExceedIpinfo();
        $actual = $ipinfo->getYourOwnIpDetails();
    }

    /**
     * Test a wrong ip response.
     * @expectedException DavidePastore\Ipinfo\Exception\WrongIpException
     */
    public function testWrongIp()
    {
        $ipinfo = new Ipinfo();
        $ipinfo->getIpGeoDetails('qwerty');
    }

    /**
     * Test a malformed ASN response.
     * @expectedException DavidePastore\Ipinfo\Exception\IpInfoException
     * @expectedExceptionMessage Wrong response
     */
    public function testMalformedASN()
    {
        $ipinfo = new Ipinfo();
        $ipinfo->getIpGeoDetails('asd');
    }

    /**
     * Test an error during the calling (e.g. a failed connection).
     */
    public function testCurlError()
    {
        $hasError = false;
        try {
            $ipinfo = new Ipinfo(array(
                'curlOptions' => array(
                    CURLOPT_PROXY => '127.0.0.1:8888',
                ),
            ));

            $ipinfo->getFullIpDetails('8.8.8.8');
        } catch (DavidePastore\Ipinfo\Exception\IpInfoException $exception) {
            $hasError = true;
            $this->assertEquals('cURL error', $exception->getMessage());
            $this->assertStringStartsWith('Failed to connect to 127.0.0.1 port 8888: Connection refused', $exception->getFullMessage());
        }

        if (!$hasError) {
            $this->fail('No exception thrown');
        }
    }
}
