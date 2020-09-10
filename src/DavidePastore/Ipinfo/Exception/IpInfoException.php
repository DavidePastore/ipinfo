<?php
namespace DavidePastore\Ipinfo\Exception;

/**
 * IpInfo generic exception.
 */
class IpInfoException extends \Exception
{
    private $fullMessage;

    public function __construct($error, $fullMessage)
    {
        parent::__construct($error);
        $this->fullMessage = $fullMessage;
    }

    public function getFullMessage()
    {
        return $this->fullMessage;
    }
}
