<?php

namespace Siarko\Api\Exception;

use JetBrains\PhpStorm\Pure;

class AppScopeNotSetException extends \Exception
{
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    #[Pure] public function __construct(
        string $message = "Application scope has not been set but is requested",
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }


}