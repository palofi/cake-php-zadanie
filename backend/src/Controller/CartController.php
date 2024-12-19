<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;
use Cake\View\JsonView;
use Exception;
use Riesenia\Cart\Cart;
use Riesenia\Cart\CartItemInterface;

class CartController extends AppController
{
    /**
     * @return array<string>
     */
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $session = $this->request->getSession();
        /** @var \Riesenia\Cart\Cart $cart */
        $cart = $session->read('Cart');

        $cartItems = [];
        $cartTotal = 0;

        if ($cart && $cart->getItems()) {
            foreach ($cart->getItems() as $item) {
                if ($item instanceof CartItemInterface) {
                    $cartItems[] = [
                        'id' => $item->getCartId(),
                        'name' => $item->getCartName(),
                        'quantity' => $item->getCartQuantity(),
                        'unit_price' => $item->getUnitPrice(),
                        'total_price' => $item->getUnitPrice() * $item->getCartQuantity(),
                        'tax_rate' => $item->getTaxRate(),
                        'tax_total' => $item->getUnitPrice() * $item->getCartQuantity() * $item->getTaxRate() / 100,
                    ];
                    $cartTotal += ($item->getUnitPrice() * $item->getCartQuantity())
                        + ($item->getUnitPrice() * $item->getCartQuantity()) * $item->getTaxRate() / 100;
                }
            }
        }

        $this->set(compact('cartTotal'));
        $this->set(compact('cartItems'));
    }

    /**
     * @param string|null $id
     * @return \App\Controller\Response|null
     */
    public function addToCart(?string $id = null)
    {
        if ($id === null) {
            $this->Flash->error(__('Invalid product selection.'));

            return $this->redirect(['action' => 'index']);
        }

        $customer = $this->request->getSession()->read('Auth');

        $cart = $this->request->getSession()->read('Cart');
        if (!$cart) {
            $cart = new Cart();
            $cart->setContext(['customer_id' => $customer->id]);
        }

        $productsTable = $this->fetchTable('Products');

        try {
            /** @var \App\Model\Entity\Product $product */
            $product = $productsTable->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Product not found.'));

            return $this->redirect(['action' => 'index']);
        }

        $cart->addItem(new CartItem($product), 1);

        $this->request->getSession()->write('Cart', $cart);

        $this->Flash->success(__('Product added to cart successfully.'));

        return $this->redirect(['controller' => 'ProductListing', 'action' => 'index']);
    }

    /**
     * @param string|null $itemId
     * @return \Cake\Http\Response|null
     */
    public function removeFromCart(?string $itemId = null): ?Response
    {
        if ($itemId === null) {
            $this->Flash->error(__('Invalid item selected for removal.'));

            return $this->redirect(['action' => 'index']);
        }

        $session = $this->request->getSession();
        $cart = $session->read('Cart');

        if (!$cart) {
            $this->Flash->error(__('Your cart is empty.'));

            return $this->redirect(['action' => 'index']);
        }

        try {
            $cart->removeItem($itemId);
            $session->write('Cart', $cart);
            $this->Flash->success(__('The item has been removed from your cart.'));
        } catch (Exception $e) {
            $this->Flash->error(__('An error occurred while removing the item: ') . $e->getMessage());
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $itemId
     * @return \Cake\Http\Response|null
     */
    public function updateQuantity(?string $itemId = null): ?Response
    {
        $this->request->allowMethod(['post']);

        // Retrieve the current quantity and change action from the form data
        $currentQuantity = (int)$this->request->getData('quantity');
        $change = $this->request->getData('change');

        // Determine the new quantity based on the action
        if ($change === 'increment') {
            $newQuantity = $currentQuantity + 1;
        } elseif ($change === 'decrement' && $currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
        } else {
            $newQuantity = $currentQuantity;
        }

        // Retrieve the cart from the session
        $session = $this->request->getSession();
        /** @var \Riesenia\Cart\Cart $cart */
        $cart = $session->read('Cart');

        // Check if the cart exists and contains the item
        if (!$cart || !$cart->hasItem($itemId)) {
            $this->Flash->error(__('Item not found in your cart.'));

            return $this->redirect(['action' => 'index']);
        }

        // Update the item's quantity
        $cart->getItem($itemId)->setCartQuantity($newQuantity);
        $session->write('Cart', $cart);

        $this->Flash->success(__('The item quantity has been updated.'));

        return $this->redirect(['action' => 'index']);
    }
}
