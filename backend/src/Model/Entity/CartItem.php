<?php declare(strict_types=1);

namespace App\Model\Entity;

use Riesenia\Cart\CartContext;
use Riesenia\Cart\CartItemInterface;

class CartItem implements CartItemInterface
{

    private ?CartContext $context = null;

    // Constructor to initialize the cart item
    public function __construct(
       private readonly Product $product,
        private readonly string $type = 'product',
        private float $quantity = 1.0
    ) {

    }

    // Get item identifier
    public function getCartId(): string
    {
        return (string)$this->product->id;
    }

    // Get type of the item
    public function getCartType(): string
    {
        return $this->type;
    }

    // Get name of the item
    public function getCartName(): string
    {
        return $this->product->name;
    }

    // Set cart context
    public function setCartContext(CartContext $context): void
    {
        $this->context = $context;
    }

    // Set cart quantity
    public function setCartQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    // Get cart quantity
    public function getCartQuantity(): float
    {
        return $this->quantity;
    }

    // Get unit price
    public function getUnitPrice(): float
    {
        return $this->product->getPrice();
    }

    // Get tax rate percentage
    public function getTaxRate(): float
    {
        return $this->product->getVatRate();
    }
}
