<?php

namespace Floky\Http\Middlewares;

use Floky\Application;
use Floky\Http\Requests\Request;

trait Middlewares
{

    private static function runMiddlewares(array $middlewares, Request $request): Request {

        if (count($middlewares) > 0) {

            $currentMiddleware = new $middlewares[0];

            $currentRequest = $currentMiddleware->handle($request);

            self::runMiddlewares(array_slice($middlewares, 1), $currentRequest);
        }

        return $currentRequest ?? $request;

    }

    private static function getMiddleware(string $name) {

        $kernel = Application::getHttpKernel();

        return $kernel->getMiddleware($name);

    }

    private static function getMiddlewareArray(array $middlewares): array {

        $result = [];

        foreach($middlewares as $middleware) {

            if ($found = self::getMiddleware($middleware)) {

                $result[] = $found;
            }
        }

        return $result;
    }
}