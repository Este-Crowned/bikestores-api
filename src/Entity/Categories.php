<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Represents a category entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Categories implements JsonSerializable {

    /**
     * @var int The unique identifier for the category.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $categoryId;

    /**
     * @var string The name of the category.
     * @ORM\Column(type="string", length=255)
     */
    private string $categoryName;

    /**
     * @var Collection|Products[] The products associated with this category.
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="category")
     */
    private Collection $products;

    /**
     * Constructor to initialize the products collection.
     */
    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * Serializes the category to JSON.
     *
     * @return array The serialized category.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getCategoryId();
        $res["name"] = $this->getCategoryName();
        $temp = [];
        foreach ($this->products as $value) {
            $temp[] = $value->getProductId();
        }
        $res["products"] = $temp;
        return $res;
    }

    /**
     * Retrieves the category ID.
     *
     * @return int The category ID.
     */
    public function getCategoryId(): int {
        return $this->categoryId;
    }

    /**
     * Retrieves the category name.
     *
     * @return string The category name.
     */
    public function getCategoryName(): string {
        return $this->categoryName;
    }

    /**
     * Sets the category name.
     *
     * @param string $name The new category name.
     */
    public function setCategoryName(string $name): void {
        $this->categoryName = $name;
    }

    /**
     * Retrieves the products associated with this category.
     *
     * @return Collection|Products[] The products associated with this category.
     */
    public function getProducts(): Collection {
        return $this->products;
    }
}
