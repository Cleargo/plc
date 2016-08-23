<?php

namespace Cleargo\Asiapay\Model\Logger\Handler;

use Cleargo\Asiapay\Model\Logger\Logger;

class Asiapay extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::ASIAPAY;

    /**
     * @var string
     */
    protected $fileName = '/var/log/asiapay.log';

    public function handle(array $record)
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        $record = $this->processRecord($record);

        $record['formatted'] = $this->getFormatter()->format($record);

        $this->write($record);

        return false === $this->bubble;
    }
}