<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestMetaTag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the tag from the request (if it exists)
        $tag = $request->input('tag');

        // Get the response from the next middleware or controller
        $response = $next($request);

        // If the tag is present, add it to the response
        if ($tag) {
            // You can modify the response body or headers as needed
            $data = json_decode($response->getContent(), true);
            $data['tag'] = $tag; // Add the tag to the response data

            // Set the modified content back to the response
            $response->setContent(json_encode($data));
        }

        return $response;
    }
}
