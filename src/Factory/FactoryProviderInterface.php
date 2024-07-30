<?php

namespace Siarko\Api\Factory;

interface FactoryProviderInterface
{

    /**
     * @param string $class
     * @return FactoryInterface
     */
    public function getFactory(string $class): FactoryInterface;
}