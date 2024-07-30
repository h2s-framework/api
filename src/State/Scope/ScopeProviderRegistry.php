<?php

namespace Siarko\Api\State\Scope;

use Siarko\Api\Exception\AppScopeNotSetException;
use Siarko\Api\Factory\ObjectCreatorInterface;
use Siarko\Api\State\AppState;

class ScopeProviderRegistry
{

    /**
     * @var string|null $provider
     */
    private static ?string $provider = null;
    private static string $scope = AppState::SCOPE_DEFAULT;


    /**
     * @param ObjectCreatorInterface $objectCreator
     */
    public function __construct(
        private readonly ObjectCreatorInterface $objectCreator
    )
    {
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        if (self::$provider === null) {
            return self::$scope;
        }
        $provider = $this->objectCreator->createObject(self::$provider);
        if(!($provider instanceof ScopeProviderInterface)){
            throw new AppScopeNotSetException('Scope provider must implement ScopeProviderInterface');
        }
        return $provider->getScope();
    }

    /**
     * @param string $providerType
     * @return void
     */
    public static function setProvider(string $providerType): void
    {
        self::$provider = $providerType;
    }

    /**
     * @param string $scope
     * @return void
     */
    public static function setScope(string $scope): void
    {
        self::$scope = $scope;
    }

}