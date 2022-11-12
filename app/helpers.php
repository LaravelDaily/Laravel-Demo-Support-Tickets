<?php

use Spatie\QueryString\QueryString;

function toggle(string $name, $toggleValue = null): QueryString
{
    $queryString = app(QueryString::class);

    return $queryString
        ->toggle($name, $toggleValue);
}

function clearQueryString(string $name)
{
    $queryString = app(QueryString::class);

    return $queryString->clear($name);
}