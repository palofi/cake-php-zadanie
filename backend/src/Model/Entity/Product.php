<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $image_path
 * @property string $vat_rate
 *
 * @property \App\Model\Entity\Category[] $categories
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'price' => true,
        'image_path' => true,
        'vat_rate' => true,
        'categories' => true,
    ];

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return ((int)$this->price) / 100;
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = (int)($price * 100);
    }

    /**
     * @return float
     */
    public function getVatRate(): float
    {
        return ((int)$this->vat_rate) / 100;
    }

    /**
     * @param string $vat_rate
     * @return void
     */
    public function setVatRate(string $vat_rate): void
    {
        $this->vat_rate = (int)($vat_rate * 100);
    }
}
