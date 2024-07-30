<?php

namespace Siarko\Api\State;


interface AppStateInterface
{

    public const CONFIG_SECTION = 'app';
    public const FIELD_APP_MODE = 'mode';

    public const FIELD_APP_SCOPE = 'scope';

    /**
     * Provides current app scope - used to separate configuration for different contexts
     * Can use ScopeProviderInterface to get scope from external source
     * @return string
     */
    public function getAppScope(): string;

    /**
     * Provides current app mode - used to separate configuration for different environments
     * Can use AppModeProviderInterface to get mode from external source
     * @return AppMode
     */
    public function getAppMode(): AppMode;

}