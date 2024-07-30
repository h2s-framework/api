<?php

declare(strict_types=1);

namespace Siarko\Api\State;

use Siarko\Api\Exception\AppScopeNotSetException;
use Siarko\Api\State\Scope\ScopeProviderRegistry;
use Siarko\Utils\DynamicDataObject;

/**
 * Class AppState
 * @api
 */
class AppState extends DynamicDataObject implements AppStateInterface
{

    public const SCOPE_DEFAULT = 'default';

    /**
     * @param ScopeProviderRegistry $scopeProviderRegistry
     * @param array $config
     */
    public function __construct(
        private readonly ScopeProviderRegistry $scopeProviderRegistry,
        array $config
    ) {
        $appConfig = $this->prepareConfig($config);
        parent::__construct($appConfig);
    }

    /**
     * @return AppMode
     */
    public function getAppMode(): AppMode
    {
        return $this->getData(AppStateInterface::FIELD_APP_MODE);
    }

    /**
     * @param AppMode $mode
     * @return bool
     */
    public function isAppMode(AppMode $mode): bool
    {
        return ($this->getAppMode() == $mode);
    }

    /**
     * @param AppMode $appMode
     * @return AppState
     */
    public function setAppMode(AppMode $appMode): static
    {
        return $this->setData(AppStateInterface::FIELD_APP_MODE, $appMode);
    }

    /**
     * @return bool
     */
    public function isDefaultScope(): bool
    {
        return $this->getAppScope() == AppState::SCOPE_DEFAULT;
    }

    /**
     * @return string
     * @throws AppScopeNotSetException
     */
    public function getAppScope(): string
    {
        return $this->getData(AppStateInterface::FIELD_APP_SCOPE) ?? throw new AppScopeNotSetException();
    }

    /**
     * @param string $appScope
     * @return $this
     */
    public function setAppScope(string $appScope): static
    {
        return $this->setData(AppStateInterface::FIELD_APP_SCOPE, $appScope);
    }

    /**
     * @param array $config
     * @return array
     */
    private function prepareConfig(array $config): array
    {
        $appConfig = $config[AppStateInterface::CONFIG_SECTION] ?? [];
        $appConfig[AppStateInterface::FIELD_APP_MODE] = AppMode::fromString($appConfig[AppStateInterface::FIELD_APP_MODE]);
        if(!array_key_exists(AppState::FIELD_APP_SCOPE, $appConfig)){
            $appConfig[AppState::FIELD_APP_SCOPE] = $this->scopeProviderRegistry->getScope();
        }
        return $appConfig;
    }

}