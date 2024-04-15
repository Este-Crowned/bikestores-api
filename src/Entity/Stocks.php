<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Entity\Products;
use Entity\Stores;
use JsonSerializable;

/**
 * Represents a stock entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="stocks")
 */
class Stocks implements JsonSerializable {

    /**
     * @var int The unique identifier for the stock.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $stockId;

    /**
     * @var int|null The quantity of the product in stock.
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $quantity;

    /**
     * @var Stores The store associated with this stock.
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId")
     */
    private Stores $store;

    /**
     * @var Products The product associated with this stock.
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="productId", referencedColumnName="productId")
     */
    private Products $product;

    /**
     * Serializes the stock to JSON.
     *
     * @return array The serialized stock.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getStockId();
        $res["quantity"] = $this->getQuantity();
        $res["store"] = $this->getStore()->getStoreId();
        $res["product"] = $this->getProduct()->getProductId();
        return $res;
    }

    /**
     * Retrieves the stock ID.
     *
     * @return int The stock ID.
     */
    public function getStockId(): int {
        return $this->stockId;
    }

    /**
     * Retrieves the quantity of the product in stock.
     *
     * @return int|null The quantity of the product in stock.
     */
    public function getQuantity(): ?int {
        return $this->quantity;
    }

    /**
     * Sets the quantity of the product in stock.
     *
     * @param int|null $qt The new quantity of the product in stock.
     */
    public function setQuantity(?int $qt): void {
        $this->quantity = $qt;
    }

    /**
     * Retrieves the store associated with this stock.
     *
     * @return Stores The store associated with this stock.
     */
    public function getStore(): Stores {
        return $this->store;
    }

    /**
     * Sets the store associated with this stock.
     *
     * @param Stores $store The new store associated with this stock.
     */
    public function setStore(Stores $store): void {
        $this->store = $store;
    }

    /**
     * Retrieves the product associated with this stock.
     *
     * @return Products The product associated with this stock.
     */
    public function getProduct(): Products {
        return $this->product;
    }

    /**
     * Sets the product associated with this stock.
     *
     * @param Products $product The new product associated with this stock.
     */
    public function setProduct(Products $product): void {
        $this->product = $product;
    }
}
