<?php

namespace Siarko\Api\State\Scope;

interface ScopeProviderInterface
{

    /**
     * @return ?string
     */
    public function getScope(): ?string;
}