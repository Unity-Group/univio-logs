<?php

declare(strict_types=1);

namespace Univio\Logs\Handler;

use Magento\Config\Setup\ConfigOptionsList;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use Monolog\Handler\StreamHandler as MonologStreamHandler;
use Monolog\Logger;

class StdoutHandler extends MonologStreamHandler
{
    /** @var string */
    private const STREAM_NAME = 'php://stdout';

    /**
     * @param DeploymentConfig $deploymentConfig
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(private readonly DeploymentConfig $deploymentConfig)
    {
        $logLevel = $this->isDebugLogAvailable() ? Logger::DEBUG : Logger::INFO;
        parent::__construct(self::STREAM_NAME, $logLevel);
    }

    /**
     * Check if debug logs are available.
     *
     * @return bool
     * @throws FileSystemException
     * @throws RuntimeException
     */
    private function isDebugLogAvailable(): bool
    {
        $isProduction = $this->deploymentConfig->get(State::PARAM_MODE);
        if ($isProduction) {
            return false;
        }

        return $this->deploymentConfig->isAvailable()
            && $this->deploymentConfig->get(ConfigOptionsList::CONFIG_PATH_DEBUG_LOGGING);
    }
}
