<?php

namespace App\Models;

use DateTime;

/**
 * Class SaleItem
 *
 * Represents an item in a sale Model in the system.
 */
class SaleItem
{
    /**
     * The unique identifier for the sale item.
     *
     * @var int
     */
    private int $id;

    /**
     * The sale identifier this item is associated with.
     *
     * @var Sale
     */
    private Sale $sale;

    /**
     * The product identifier of the item.
     *
     * @var Product
     */
    private Product $product;

    /**
     * The quantity of the product sold.
     *
     * @var int
     */
    private int $quantity;

    /**
     * The price per unit of the product.
     *
     * @var float
     */
    private float $pricePerUnit;

    /**
     * The tax per unit of the product.
     *
     * @var float
     */
    private float $taxPerUnit;

    /**
     * The total price for the quantity of items sold.
     *
     * @var float
     */
    private float $totalPrice;

    /**
     * The total tax for the quantity of items sold.
     *
     * @var float
     */
    private float $totalTax;

    /**
     * The date and time when the sale item was created.
     *
     * @var DateTime
     */
    private DateTime $createdAt;

    /**
     * The date and time when the sale item was last updated.
     *
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * The date and time when the sale item was deleted, if applicable.
     *
     * @var DateTime|null
     */
    private ?DateTime $deletedAt;

    /**
     * Get the unique identifier for the sale item.
     *
     * @return int The unique identifier for the sale item.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier for the sale item.
     *
     * @param int $id The unique identifier for the sale item.
     * @return self Returns instance of the SaleItem class.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the sale this item is associated with.
     *
     * @return Sale The sale.
     */
    public function getSale(): Sale
    {
        return $this->sale;
    }

    /**
     * Set the sale this item is associated with.
     *
     * @param Sale $sale The sale.
     * @return self Returns instance of the SaleItem class.
     */
    public function setSale(Sale $sale): self
    {
        $this->sale = $sale;
        return $this;
    }

    /**
     * Get the product this item is associated with.
     *
     * @return Product The product.
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Set the product this item is associated with.
     *
     * @param Product $product The product.
     * @return self Returns instance of the SaleItem class.
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Get the quantity of the product sold.
     *
     * @return int The quantity of the product sold.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set the quantity of the product sold.
     *
     * @param int $quantity The quantity of the product sold.
     * @return self Returns instance of the SaleItem class.
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get the price per unit of the product.
     *
     * @return float The price per unit of the product.
     */
    public function getPricePerUnit(): float
    {
        return $this->pricePerUnit;
    }

    /**
     * Set the price per unit of the product.
     *
     * @param float $pricePerUnit The price per unit of the product.
     * @return self Returns instance of the SaleItem class.
     */
    public function setPricePerUnit(float $pricePerUnit): self
    {
        $this->pricePerUnit = $pricePerUnit;
        return $this;
    }

    /**
     * Get the tax per unit of the product.
     *
     * @return float The tax per unit of the product.
     */
    public function getTaxPerUnit(): float
    {
        return $this->taxPerUnit;
    }

    /**
     * Set the tax per unit of the product.
     *
     * @param float $taxPerUnit The tax per unit of the product.
     * @return self Returns instance of the SaleItem class.
     */
    public function setTaxPerUnit(float $taxPerUnit): self
    {
        $this->taxPerUnit = $taxPerUnit;
        return $this;
    }

    /**
     * Get the total price for the quantity of items sold.
     *
     * @return float The total price for the quantity of items sold.
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * Set the total price for the quantity of items sold.
     *
     * @param float $totalPrice The total price for the quantity of items sold.
     * @return self Returns instance of the SaleItem class.
     */
    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * Get the total tax for the quantity of items sold.
     *
     * @return float The total tax for the quantity of items sold.
     */
    public function getTotalTax(): float
    {
        return $this->totalTax;
    }

    /**
     * Set the total tax for the quantity of items sold.
     *
     * @param float $totalTax The total tax for the quantity of items sold.
     * @return self Returns instance of the SaleItem class.
     */
    public function setTotalTax(float $totalTax): self
    {
        $this->totalTax = $totalTax;
        return $this;
    }

    /**
     * Get the creation date and time of the sale item.
     *
     * @return DateTime The creation date and time.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date and time of the sale item.
     *
     * @param DateTime $createdAt The creation date and time.
     * @return self Returns instance of the SaleItem class.
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the update date and time of the sale item, if updated.
     *
     * @return DateTime|null The update date and time or null if not updated.
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set the update date and time of the sale item.
     *
     * @param DateTime|null $updatedAt The update date and time or null.
     * @return self Returns instance of the SaleItem class.
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the deletion date and time of the sale item, if deleted.
     *
     * @return DateTime|null The deletion date and time or null if not deleted.
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Set the deletion date and time of the sale item.
     *
     * @param DateTime|null $deletedAt The deletion date and time or null.
     * @return self Returns instance of the SaleItem class.
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Converts the SaleItem object to an associative array.
     *
     * @return array The sale item data as an associative array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'product' => $this->getProduct()->toArray(),
            'quantity' => $this->getQuantity(),
            'price_per_unit' => $this->getPricePerUnit(),
            'tax_per_unit' => $this->getTaxPerUnit(),
            'total_price' => $this->getTotalPrice(),
            'total_tax' => $this->getTotalTax(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->getDeletedAt()?->format('Y-m-d H:i:s')
        ];
    }
}
