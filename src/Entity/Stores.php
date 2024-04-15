<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Represents a store entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="stores")
 */
class Stores implements JsonSerializable {

    /**
     * @var int The unique identifier for the store.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $storeId;

    /**
     * @var string The name of the store.
     * @ORM\Column(type="string", length=255)
     */
    private string $storeName;

    /**
     * @var string|null The phone number of the store.
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private ?string $phone;

    /**
     * @var string|null The email address of the store.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @var string|null The street address of the store.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $street;

    /**
     * @var string|null The city of the store.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    /**
     * @var string|null The state of the store.
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $state;

    /**
     * @var string|null The ZIP code of the store.
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private ?string $zipCode;

    /**
     * @var Collection|Employees[] The employees associated with this store.
     * @ORM\OneToMany(targetEntity=Employees::class, mappedBy="store")
     */
    private Collection $employees;

    /**
     * @var Collection|Stocks[] The stocks associated with this store.
     * @ORM\OneToMany(targetEntity=Stocks::class, mappedBy="store")
     */
    private Collection $stocks;

    /**
     * Constructor to initialize the employees and stocks collections.
     */
    public function __construct() {
        $this->employees = new ArrayCollection();
        $this->stocks = new ArrayCollection();
    }

    /**
     * Serializes the store to JSON.
     *
     * @return array The serialized store.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getStoreId();
        $res["name"] = $this->getStoreName();
        $res["phone"] = $this->getPhone();
        $res["email"] = $this->getEmail();
        $res["state"] = $this->getState();
        $res["zipcode"] = $this->getZipCode();
        $res["city"] = $this->getCity();
        $res["street"] = $this->getStreet();
        $temp = [];
        foreach ($this->employees as $value) {
            $temp[] = $value->getEmployeeId();
        }
        $res["employees"] = $temp;
        $temp2 = [];
        foreach ($this->stocks as $value) {
            $temp2[] = $value->getStockId();
        }
        $res["stocks"] = $temp2;
        return $res;
    }

    /**
     * Retrieves the store ID.
     *
     * @return int The store ID.
     */
    public function getStoreId(): int {
        return $this->storeId;
    }

    /**
     * Sets the store ID.
     *
     * @param int $storeId The new store ID.
     */
    public function setStoreId(int $storeId): void {
        $this->storeId = $storeId;
    }

    /**
     * Retrieves the store name.
     *
     * @return string The store name.
     */
    public function getStoreName(): string {
        return $this->storeName;
    }

    /**
     * Sets the store name.
     *
     * @param string $storeName The new store name.
     */
    public function setStoreName(string $storeName): void {
        $this->storeName = $storeName;
    }

    /**
     * Retrieves the store phone number.
     *
     * @return string|null The store phone number.
     */
    public function getPhone(): ?string {
        return $this->phone;
    }

    /**
     * Sets the store phone number.
     *
     * @param string|null $phone The new store phone number.
     */
    public function setPhone(?string $phone): void {
        $this->phone = $phone;
    }

    /**
     * Retrieves the store email address.
     *
     * @return string|null The store email address.
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Sets the store email address.
     *
     * @param string|null $email The new store email address.
     */
    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    /**
     * Retrieves the store street address.
     *
     * @return string|null The store street address.
     */
    public function getStreet(): ?string {
        return $this->street;
    }

    /**
     * Sets the store street address.
     *
     * @param string|null $street The new store street address.
     */
    public function setStreet(?string $street): void {
        $this->street = $street;
    }

    /**
     * Retrieves the store city.
     *
     * @return string|null The store city.
     */
    public function getCity(): ?string {
        return $this->city;
    }

    /**
     * Sets the store city.
     *
     * @param string|null $city The new store city.
     */
    public function setCity(?string $city): void {
        $this->city = $city;
    }

    /**
     * Retrieves the store state.
     *
     * @return string|null The store state.
     */
    public function getState(): ?string {
        return $this->state;
    }

    /**
     * Sets the store state.
     *
     * @param string|null $state The new store state.
     */
    public function setState(?string $state): void {
        $this->state = $state;
    }

    /**
     * Retrieves the store ZIP code.
     *
     * @return string|null The store ZIP code.
     */
    public function getZipCode(): ?string {
        return $this->zipCode;
    }

    /**
     * Sets the store ZIP code.
     *
     * @param string|null $zipCode The new store ZIP code.
     */
    public function setZipCode(?string $zipCode): void {
        $this->zipCode = $zipCode;
    }

    /**
     * Retrieves the employees associated with this store.
     *
     * @return Collection|Employees[] The employees associated with this store.
     */
    public function getEmployees(): Collection {
        return $this->employees;
    }

    /**
     * Retrieves the stocks associated with this store.
     *
     * @return Collection|Stocks[] The stocks associated with this store.
     */
    public function getStocks(): Collection {
        return $this->stocks;
    }
}
