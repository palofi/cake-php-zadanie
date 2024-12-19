<?php
/**
 * @var \App\View\AppView $this
 * @var array $cartItems
 * @var float $cartTotal
 */
?>
<div class="row">
    <div class="column column-100">
        <div class="cart index content">
            <h3><?= __('Shopping Cart') ?></h3>
            <?php if (!empty($cartItems)): ?>
                <table>
                    <thead>
                    <tr>
                        <th><?= __('Product Name') ?></th>
                        <th><?= __('Unit Price') ?></th>
                        <th><?= __('Quantity') ?></th>
                        <th><?= __('Total Price') ?></th>
                        <th><?= __('Tax Rate') ?></th>
                        <th><?= __('Tax Total') ?></th>
                        <th colspan="3"><?= __('Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Cart', 'action' => 'updateQuantity', $item['id']],
                            'type' => 'post',
                            'class' => 'quantity-form',
                        ]) ?>
                        <tr>
                            <td><?= h($item['name']) ?></td>
                            <td><?= $this->Number->currency($item['unit_price']) ?></td>

                            <td><?= h($item['quantity']) ?></td>

                            <td><?= $this->Number->currency($item['total_price']) ?></td>
                            <td><?= h($item['tax_rate']) ?>%</td>
                            <td><?= $this->Number->currency($item['tax_total']) ?></td>
                            <td>


                                <?= $this->Form->button('-', [
                                    'type' => 'submit',
                                    'name' => 'change',
                                    'value' => 'decrement',
                                    'class' => 'decrement-quantity',
                                ]) ?>
                            </td>
                            <td>
                                <?= $this->Form->control('quantity', [
                                    'hidden' => true,
                                    'type' => 'number',
                                    'label' => false,
                                    'min' => 1,
                                    'value' => $item['quantity'],
                                    'class' => 'quantity-input',
                                    'readonly' => true,
                                ]) ?>
                                <?= $this->Form->button('+', [
                                    'type' => 'submit',
                                    'name' => 'change',
                                    'value' => 'increment',
                                    'class' => 'increment-quantity',
                                ]) ?>
                            </td>
                            <td>
                                <?= $this->Html->link(__('Remove'), ['action' => 'removeFromCart', $item['id']], ['confirm' => __('Are you sure you want to remove {0} from your cart?', $item['name'])]) ?>
                            </td>
                        </tr>
                        <?= $this->Form->end() ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="cart-total">
                    <strong><?= __('Cart Total:') ?></strong> <?= $this->Number->currency($cartTotal) ?>
                </div>
                <?= $this->Html->link(__('Proceed to Checkout'), [], ['class' => 'button float-right']) ?>
            <?php else: ?>
                <p><?= __('Your cart is empty.') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

