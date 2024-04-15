<?php

namespace App\Models;

use DateTime;

/**
 * Class Sale
 *
 * Represents a sale Model in the system.
 */
class Sale
{
    /**
     * The unique identifier for the sale.
     *
     * @var int
     */
    private int $id;

    /**
     * The total amount of the sale.
     *
     * @var float
     */
    private float $totalAmount;

    /**
     * The total tax of the sale.
     *
     * @var float
     */
    private float $totalTax;

    /**
     * The list of items in the sale.
     *
     * @var SaleItem[] An array of SaleItem objects.
     */
    private array $items = [];

    /**
     * The date and time when the sale was created.
     *
     * @var DateTime
     */
    private DateTime $createdAt;

    /**
     * The date and time when the sale was last updated.
     *
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * The date and time when the sale was deleted, if applicable.
     *
     * @var DateTime|null
     */
    private ?DateTime $deletedAt;

    /**
     * Get the sale ID.
     *
     * @return int The unique identifier for the sale.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the sale ID.
     *
     * @param int $id The unique identifier for the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the total amount of the sale.
     *
     * @return float The total amount for the sale.
     */
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    /**
     * Set the total amount of the sale.
     *
     * @param float $totalAmount The total amount for the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    /**
     * Get the total tax of the sale.
     *
     * @return float The total tax for the sale.
     */
    public function getTotalTax(): float
    {
        return $this->totalTax;
    }

    /**
     * Set the total tax of the sale.
     *
     * @param float $totalTax The total tax for the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setTotalTax(float $totalTax): self
    {
        $this->totalTax = $totalTax;
        return $this;
    }

    /**
     * Get the date and time when the sale was created.
     *
     * @return DateTime The creation date and time of the sale.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the date and time when the sale was created.
     *
     * @param DateTime $createdAt The creation date and time of the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the date and time when the sale was last updated.
     *
     * @return DateTime|null The last update date and time of the sale or null if not updated.
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set the date and time when the sale was last updated.
     *
     * @param DateTime|null $updatedAt The last update date and time of the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the date and time when the sale was deleted.
     *
     * @return DateTime|null The deletion date and time of the sale or null if not deleted.
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Set the date and time when the sale was deleted.
     *
     * @param DateTime|null $deletedAt The deletion date and time of the sale.
     * @return self Returns instance of the Sale class.
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Get the list of sale items.
     *
     * @return SaleItem[] An array of SaleItem objects.
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Set the list of sale items.
     *
     * @param SaleItem[] $items An array of SaleItem objects.
     * @return self Returns instance of the Sale class.
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Adds a sale item to the list of items.
     *
     * @param SaleItem $item The sale item to add.
     * @return self Returns instance of the Sale class.
     */
    public function addItem(SaleItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Removes a sale item from the list of items.
     *
     * @param SaleItem $item The sale item to remove.
     * @return self Returns instance of the Sale class.
     */
    public function removeItem(SaleItem $item): self
    {
        $index = array_search($item, $this->items);
        if ($index !== false) {
            array_splice($this->items, $index, 1);
        }
        return $this;
    }

    /**
     * Converts the Sale object to an associative array.
     *
     * @return array The sale data as an associative array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'items' => $this->getItems(),
            'total_amount' => $this->getTotalAmount(),
            'total_tax' => $this->getTotalTax(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->getDeletedAt()?->format('Y-m-d H:i:s')
        ];
    }
}
