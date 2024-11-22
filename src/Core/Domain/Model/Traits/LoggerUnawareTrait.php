<?php

namespace App\Core\Domain\Model\Traits;

use Psr\Log\LoggerInterface;

trait LoggerUnawareTrait
{
    private ?LoggerInterface $logger = null;

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    private function logDebug($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->debug($message, $context);
        }
    }

    private function logNotice($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->notice($message, $context);
        }
    }

    private function logInfo($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->info($message, $context);
        }
    }

    private function logWarning($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->warning($message, $context);
        }
    }

    private function logError($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->error($message, $context);
        }
    }

    private function logCritical($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->critical($message, $context);
        }
    }

    private function logEmergency($message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->emergency($message, $context);
        }
    }

    private function log($level, $message, array $context = array()): void
    {
        if ($this->isLoggerAvailable()) {
            $this->logger->log($level, $message, $context);
        }
    }

    private function isLoggerAvailable(): bool
    {
        return null !== $this->logger;
    }
}
