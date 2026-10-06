<?php

namespace App\Exceptions;

use Exception;

class CartException extends Exception
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
                'message' => $this->getMessage(),
                'error' => 'An error occurred with the shopping cart.'
            ], 400);
        }

        return redirect()->back()
            ->with('error', $this->getMessage());
    }
}
