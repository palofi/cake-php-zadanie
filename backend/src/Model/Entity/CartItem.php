<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Riesenia\Cart\CartContext;
use Riesenia\Cart\CartItemInterface;

class CartItem implements CartItemInterface
{
    private ?CartContext $context = null;

    /**
     * @param \App\Model\Entity\Product $product
     * @param string $type
     * @param float $quantity
     */
    public function __construct(
        private readonly Product $product,
        private readonly string $type = 'product',
        private float $quantity = 1.0
    ) {
    }

    /**
     * @return string
     */
    public function getCartId(): string
    {
        return (string)$this->product->id;
    }

    /**
     * @return string
     */
    public function getCartType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCartName(): string
    {
        return $this->product->name;
    }

    /**
     * @param \Riesenia\Cart\CartContext $context
     * @return void
     */
    public function setCartContext(CartContext $context): void
    {
        $this->context = $context;
    }

    /**
     * @param float $quantity
     * @return void
     */
    public function setCartQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getCartQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->product->getPrice();
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->product->getVatRate();
    }
}
