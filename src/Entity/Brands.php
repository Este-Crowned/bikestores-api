<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Represents a brand entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="brands")
 */
class Brands implements JsonSerializable {

    /**
     * @var int The unique identifier for the brand.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $brandId;

    /**
     * @var string The name of the brand.
     * @ORM\Column(type="string", length=255)
     */
    private string $brandName;

    /**
     * @var Collection|Products[] The products associated with this brand.
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="brandId")
     */
    private Collection $products;

    /**
     * Constructor to initialize the products collection.
     */
    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * Serializes the brand to JSON.
     *
     * @return array The serialized brand.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getBrandId();
        $res["name"] = $this->getBrandName();
        $temp = [];
        foreach ($this->products as $value) {
            $temp[] = $value->getProductId();
        }
        $res["products"] = $temp;
        return $res;
    }

    /**
     * Retrieves the brand ID.
     *
     * @return int The brand ID.
     */
    public function getBrandId(): int {
        return $this->brandId;
    }

    /**
     * Retrieves the brand name.
     *
     * @return string The brand name.
     */
    public function getBrandName(): string {
        return $this->brandName;
    }

    /**
     * Sets the brand name.
     *
     * @param string $name The new brand name.
     */
    public function setBrandName(string $name): void {
        $this->brandName = $name;
    }
}
