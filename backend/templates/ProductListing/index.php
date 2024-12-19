<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var iterable<\App\Model\Entity\Category> $categories
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading">Categories</h4>
            <?= $this->Html->link(__("All"), ['action' => ''], ['class' => 'side-nav-item']) ?>
            <?php foreach ($categories as $category): ?>
                <?= $this->Html->link(__($category->name), ['action' => '', $category->id], ['class' => 'side-nav-item']) ?>
            <?php endforeach; ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="products index content">
            <h3><?= __('Products') ?></h3>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('image_path') ?></th>
                        <th><?= $this->Paginator->sort('price') ?></th>
                        <th><?= $this->Paginator->sort('vat_rate') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $this->Number->format($product->id) ?></td>
                            <td><?= h($product->name) ?></td>

                            <td>
                                <?= '<a href="/img/' . $product->image_path . '">' . $product->image_path . '</a>'; ?>
                            </td>
                            <td><?= $this->Number->currency($product->getPrice()) ?></td>
                            <td><?= $this->Number->format($product->getVatRate()) ?>%</td>
                            <td class="actions">
                                <?= $this->Html->link(__('Add to Cart'),  ['controller' => 'Cart', 'action' => 'addToCart', $product->id]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
