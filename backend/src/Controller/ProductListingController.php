<?php
declare(strict_types=1);

namespace App\Controller;

class ProductListingController extends AppController
{
    /**
     * @param ?string $id
     * @return void
     */
    public function index(?string $id = null): void
    {
        // Load the Products table
        $productsTable = $this->fetchTable('Products');

        // Initialize the query to find all products
        $query = $productsTable->find();

        // If a category ID is provided, filter products by that category
        if ($id !== null) {
            $query
                ->matching('Categories', function ($q) use ($id) {
                    return $q->where(['Categories.id' => $id]);
                });
        }

        // Paginate the products query
        $products = $this->paginate($query);

        // Load and paginate the Categories table
        $categoriesTable = $this->fetchTable('Categories');
        $categories = $this->paginate($categoriesTable);

        // Set variables to be used in the view
        $this->set(compact('products', 'categories'));
    }
}
