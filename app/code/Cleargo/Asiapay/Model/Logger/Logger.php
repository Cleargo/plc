<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Logger;

class Logger extends \Monolog\Logger
{
    const ASIAPAY = 9990;

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     *
     * @var array $levels Logging levels
     */
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
        9990 => 'ASIAPAY'
    );

    public function addAsiapayLog($message, array $context = array())
    {
        return $this->addRecord(static::ASIAPAY, $message, $context);
    }

    public function asiapayLog($message, array $context = array())
    {
        return $this->addAsiapayLog($message, $context);
    }
}