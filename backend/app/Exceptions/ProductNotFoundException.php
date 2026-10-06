<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product not found.',
                'error' => 'The requested product does not exist or has been removed.'
            ], 404);
        }

        return redirect()->route('products')
            ->with('error', 'Product not found. The requested product does not exist or has been removed.');
    }
}
