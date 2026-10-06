<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $availableQuantity;

    public function __construct($message = "Insufficient stock available.", $availableQuantity = 0)
    {
        parent::__construct($message);
        $this->availableQuantity = $availableQuantity;
    }

    /**
     * Get the available quantity.
     *
     * @return int
     */
    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

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
                'available_quantity' => $this->availableQuantity,
                'error' => 'Insufficient stock available for this product.'
            ], 422);
        }

        return redirect()->back()
            ->with('error', $this->getMessage() . ' Available: ' . $this->availableQuantity);
    }
}
