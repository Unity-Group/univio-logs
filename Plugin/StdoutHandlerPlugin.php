<?php

namespace Univio\Logs\Plugin;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Univio\Logs\Handler\StdoutHandlerFactory;

class StdoutHandlerPlugin
{
    /** @var string */
    private const CONFIG_PATH_STDOUT_LOGS_ENABLED = 'logs/stdout_enabled';

    /**
     * @param DeploymentConfig $deploymentConfig
     * @param StdoutHandlerFactory $stdoutHandlerFactory
     */
    public function __construct(
        private readonly DeploymentConfig $deploymentConfig,
        private readonly StdoutHandlerFactory $stdoutHandlerFactory
    ) {
    }

    /**
     * Check if stdout logs are enabled.
     *
     * @return bool
     * @throws FileSystemException
     * @throws RuntimeException
     */
    private function useStdout(): bool
    {
        return $this->deploymentConfig->isAvailable()
            && $this->deploymentConfig->get(self::CONFIG_PATH_STDOUT_LOGS_ENABLED);
    }

    /**
     * Add stdout handler if stdout logs are enabled.
     *
     * @param Logger $subject
     * @param HandlerInterface $handler
     * @return HandlerInterface[]
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function beforePushHandler(Logger $subject, HandlerInterface $handler): array
    {
        if ($this->useStdout()) {
            return [$this->stdoutHandlerFactory->create()];
        }

        return [$handler];
    }
}
