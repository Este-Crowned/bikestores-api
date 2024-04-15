<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity\Brands;
use Entity\Categories;
use JsonSerializable;

/**
 * Represents a product entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Products implements JsonSerializable {

    /**
     * @var int The unique identifier for the product.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $productId;

    /**
     * @var string The name of the product.
     * @ORM\Column(type="string", length=255)
     */
    private string $productName;

    /**
     * @var Brands The brand associated with this product.
     * @ORM\ManyToOne(targetEntity=Brands::class, inversedBy="products")
     * @ORM\JoinColumn(name="brandId", referencedColumnName="brandId")
     */
    private Brands $brandId;

    /**
     * @var Categories The category associated with this product.
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products")
     * @ORM\JoinColumn(name="category", referencedColumnName="categoryId")
     */
    private Categories $category;

    /**
     * @var int The model year of the product.
     * @ORM\Column(type="integer")
     */
    private int $modelYear;

    /**
     * @var float The list price of the product.
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $listPrice;

    /**
     * Serializes the product to JSON.
     *
     * @return array The serialized product.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getProductId();
        $res["name"] = $this->getProductName();
        $res["brand"] = [$this->getBrandId()->getBrandId(), $this->getBrandId()->getBrandName()];
        $res["category"] = [$this->getCategory()->getCategoryId(), $this->getCategory()->getCategoryName()];
        $res["year"] = $this->getModelYear();
        $res["price"] = $this->getListPrice();
        return $res;
    }

    /**
     * Retrieves the product ID.
     *
     * @return int The product ID.
     */
    public function getProductId(): int {
        return $this->productId;
    }

    /**
     * Retrieves the product name.
     *
     * @return string The product name.
     */
    public function getProductName(): string {
        return $this->productName;
    }

    /**
     * Sets the product name.
     *
     * @param string $name The new product name.
     */
    public function setProductName(string $name): void {
        $this->productName = $name;
    }

    /**
     * Retrieves the brand associated with this product.
     *
     * @return Brands The brand associated with this product.
     */
    public function getBrandId(): Brands {
        return $this->brandId;
    }

    /**
     * Sets the brand associated with this product.
     *
     * @param Brands $brand The new brand associated with this product.
     */
    public function setBrandId(Brands $brand): void {
        $this->brandId = $brand;
    }

    /**
     * Retrieves the category associated with this product.
     *
     * @return Categories The category associated with this product.
     */
    public function getCategory(): Categories {
        return $this->category;
    }

    /**
     * Sets the category associated with this product.
     *
     * @param Categories $category The new category associated with this product.
     */
    public function setCategory(Categories $category): void {
        $this->category = $category;
    }

    /**
     * Retrieves the model year of the product.
     *
     * @return int The model year of the product.
     */
    public function getModelYear(): int {
        return $this->modelYear;
    }

    /**
     * Sets the model year of the product.
     *
     * @param int $year The new model year of the product.
     */
    public function setModelYear(int $year): void {
        $this->modelYear = $year;
    }

    /**
     * Retrieves the list price of the product.
     *
     * @return float The list price of the product.
     */
    public function getListPrice(): float {
        return $this->listPrice;
    }

    /**
     * Sets the list price of the product.
     *
     * @param float $price The new list price of the product.
     */
    public function setListPrice(float $price): void {
        $this->listPrice = $price;
    }
}
