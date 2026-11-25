<?php
namespace DavidePastore\Ipinfo;

/**
 * Ipinfo that reads its responses from a local fixture returning a rate limit error.
 */
class RateLimitExceedIpinfo extends Ipinfo
{
    const BASE_URL = 'file://' . __DIR__ . '/fixtures/rate-limit-exceeded/';
}
