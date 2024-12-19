<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\CartItem;
use App\Model\Entity\Product;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\View\JsonView;
use Riesenia\Cart\Cart;
use Riesenia\Cart\CartItemInterface;

class CartController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }


    public function index()
    {
        $session = $this->request->getSession();
        /** @var Cart $cart */
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
                        'total_price' => ($item->getUnitPrice() * $item->getCartQuantity()),
                        'tax_rate' => $item->getTaxRate(),
                        'tax_total' => ($item->getUnitPrice() * $item->getCartQuantity()) * ($item->getTaxRate() / 100),
                    ];
                    $cartTotal += ($item->getUnitPrice() * $item->getCartQuantity()) + ($item->getUnitPrice() * $item->getCartQuantity()) * ($item->getTaxRate() / 100);
                }
            }
        }

//        $cartTotal = $cart->getTotal();

        $this->set(compact('cartTotal'));
        $this->set(compact('cartItems'));
    }


    public function addToCart($id = null)
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
            /** @var Product $product */
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


    public function removeFromCart($itemId = null)
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
        } catch (\Exception $e) {
            $this->Flash->error(__('An error occurred while removing the item: ') . $e->getMessage());
        }

        return $this->redirect(['action' => 'index']);
    }


    public function updateQuantity($itemId = null)
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
        /** @var Cart $cart */
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