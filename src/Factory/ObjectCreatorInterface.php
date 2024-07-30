<?php

namespace Siarko\Api\Factory;

interface ObjectCreatorInterface
{

    /**
     * Create object of given type and pass $data to constructor
     * @param string $className
     * @param array $data
     * @return object
     */
    public function createObject(string $className, array $data = []): object;

}