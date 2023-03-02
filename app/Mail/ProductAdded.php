<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.product_added')
                    ->with([
                        'productName' => $this->product->name,
                        'productPrice' => $this->product->price,
                        'productStatus' => $this->product->status,
                        'productType' => $this->product->type,
                        'userName' => $this->product->user->name,
                    ]);
    }
}
