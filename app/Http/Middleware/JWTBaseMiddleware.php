<?php

namespace App\Http\Middleware;

class JWTBaseMiddleware extends \Tymon\JWTAuth\Middleware\BaseMiddleware
{
    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  array   $errors
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $errors, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        return $response ?: $this->response->json(['errors' => $errors], $status);
    }
}