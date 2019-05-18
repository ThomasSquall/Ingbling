<?php

namespace Ingbling\MVC;

abstract class MiddlewareBase
{
    public abstract function resolve($endpoint);
}