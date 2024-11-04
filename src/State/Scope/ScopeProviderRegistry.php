<?php

namespace Siarko\Api\State\Scope;

use MJS\TopSort\CircularDependencyException;
use MJS\TopSort\ElementNotFoundException;
use MJS\TopSort\Implementations\FixedArraySort;
use Siarko\Api\Exception\AppScopeNotSetException;
use Siarko\Api\Exception\AppScopeProviderSortingException;
use Siarko\Api\Factory\ObjectCreatorInterface;
use Siarko\Api\State\AppState;

class ScopeProviderRegistry
{

    public const SCOPE_PROVIDER_BEFORE = 'before';
    public const SCOPE_PROVIDER_AFTER = 'after';
    public const SCOPE_PROVIDER_CLASS = 'class';


    private static  string $defaultScope = AppState::SCOPE_DEFAULT;

    private static ?string $scope = null;

    /**
     * @param ObjectCreatorInterface $objectCreator
     * @param ScopeProviderInterface $scopeProviders
     */
    public function __construct(
        private readonly ObjectCreatorInterface $objectCreator,
        private readonly array                  $scopeProviders = []
    )
    {
    }

    /**
     * @return string
     * @throws AppScopeNotSetException
     */
    public function getScope(): string
    {
        if(!self::$scope){
            self::$scope = $this->findScope();
        }
        return self::$scope;
    }

    /**
     * @param string $defaultScope
     * @return void
     */
    public static function setScope(string $defaultScope): void
    {
        self::$scope = $defaultScope;
    }

    /**
     * @return string
     * @throws AppScopeNotSetException
     */
    protected function findScope(): string
    {
        if(empty($this->scopeProviders)){
            return self::$defaultScope;
        }

        foreach($this->sort($this->scopeProviders) as $typeName){
            $provider = $this->objectCreator->createObject($typeName);
            if (!($provider instanceof ScopeProviderInterface)) {
                throw new AppScopeNotSetException('Scope provider must implement ScopeProviderInterface - '.$typeName);
            }
            if(!is_null($scope = $provider->getScope())){
                return $scope;
            }
        }

        return self::$defaultScope;
    }

    /**
     * @param array $scopeProviders
     * @return array
     * @throws AppScopeProviderSortingException
     */
    private function sort(array $scopeProviders): array
    {
        try{
            $result = [];
            $sorter = new FixedArraySort();
            foreach ($this->createDependencies($scopeProviders) as $id => $dependencies) {
                $sorter->add($id, $dependencies);
            }
            foreach ($sorter->sort() as $id) {
                $value = $scopeProviders[$id];
                $result[] = (is_array($value) ? $value[self::SCOPE_PROVIDER_CLASS] : $value);
            }
            return $result;
        }catch (CircularDependencyException $e){
            throw new AppScopeProviderSortingException('Circular dependency in scope providers - '.$e->getMessage());
        }catch (ElementNotFoundException $e) {
            throw new AppScopeProviderSortingException('Scope Provider with ID not found in scope providers - ' . $e->getMessage());
        }
    }

    /**
     * @param array $scopeProviders
     * @return array
     */
    private function createDependencies(array $scopeProviders): array
    {
        $result = [];
        foreach ($scopeProviders as $id => $data) {
            $result[$id] = $result[$id] ?? [];
            if(is_array($data)){
                if($before = $data[self::SCOPE_PROVIDER_BEFORE] ?? null){
                    $result[$before][] = $id;
                }
                if($after = $data[self::SCOPE_PROVIDER_AFTER] ?? null){
                    $result[$id][] = $after;
                }
            }
        }
        return $result;
    }

}