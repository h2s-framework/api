<?php

namespace Siarko\Api\Factory;

interface FactoryInterface
{

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data = []): object;
}