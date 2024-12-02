<?php

namespace App\Core\Domain\Model\Traits;

use Psr\Log\LoggerInterface;

trait LoggerUnawareTrait
{
    private ?LoggerInterface $logger = null;

    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /** @param array<int, bool|int|string> $context */
    private function logNotice(string $message, array $context = []): void
    {
        $this->logger?->notice($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logInfo(string $message, array $context = []): void
    {
        $this->logger?->info($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logDebug(string $message, array $context = []): void
    {
        $this->logger?->debug($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logError(string $message, array $context = []): void
    {
        $this->logger?->error($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logCritical(string $message, array $context = []): void
    {
        $this->logger?->critical($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logEmergency(string $message, array $context = []): void
    {
        $this->logger?->emergency($message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function log(mixed $level, string $message, array $context = []): void
    {
        $this->logger?->log($level, $message, $context);
    }

    /** @param array<int, bool|int|string> $context */
    private function logWarning(string $message, array $context = []): void
    {
        $this->logger?->warning($message, $context);
    }

    private function isLoggerAvailable(): bool
    {
        return null !== $this->logger;
    }
}
