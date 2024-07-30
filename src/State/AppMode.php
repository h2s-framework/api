<?php

namespace Siarko\Api\State;

/**
 * Application mode enum
 * @api
 */
enum AppMode
{
    case DEV;
    case PROD;

    /**
     * @param string|null $name
     * @return AppMode
     */
    public static function fromString(?string $name): AppMode
    {
        if($name === null){
            return self::PROD;
        }
        if(strtoupper($name) == self::DEV->name){
            return self::DEV;
        }
        return self::PROD;
    }
}