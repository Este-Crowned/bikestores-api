<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Entity\Stores;
use JsonSerializable;

/**
 * Represents an employee entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employees implements JsonSerializable {

    /**
     * @var int The unique identifier for the employee.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $employeeId;

    /**
     * @var string The name of the employee.
     * @ORM\Column(type="string", length=255)
     */
    private string $employeeName;

    /**
     * @var string The email address of the employee.
     * @ORM\Column(type="string", length=255)
     */
    private string $employeeEmail;

    /**
     * @var string The password of the employee.
     * @ORM\Column(type="string", length=255)
     */
    private string $employeePassword;

    /**
     * @var string The role of the employee.
     * @ORM\Column(type="string", length=255)
     */
    private string $employeeRole;

    /**
     * @var Stores The store associated with this employee.
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="employees")
     * @ORM\JoinColumn(name="store", referencedColumnName="storeId")
     */
    private Stores $store;

    /**
     * Serializes the employee to JSON.
     *
     * @return array The serialized employee.
     */
    public function jsonSerialize(): array {
        $res = [];
        $res["id"] = $this->getEmployeeId();
        $res["name"] = $this->getEmployeeName();
        $res["email"] = $this->getEmployeeEmail();
        $res["password"] = $this->getEmployeePassword();
        $res["role"] = $this->getEmployeeRole();
        $res["store"] = $this->getStore()->getStoreId();
        return $res;
    }

    /**
     * Retrieves the employee ID.
     *
     * @return int The employee ID.
     */
    public function getEmployeeId(): int {
        return $this->employeeId;
    }

    /**
     * Retrieves the employee name.
     *
     * @return string The employee name.
     */
    public function getEmployeeName(): string {
        return $this->employeeName;
    }

    /**
     * Sets the employee name.
     *
     * @param string $employeeName The new employee name.
     */
    public function setEmployeeName(string $employeeName): void {
        $this->employeeName = $employeeName;
    }

    /**
     * Retrieves the employee email.
     *
     * @return string The employee email.
     */
    public function getEmployeeEmail(): string {
        return $this->employeeEmail;
    }

    /**
     * Sets the employee email.
     *
     * @param string $employeeEmail The new employee email.
     */
    public function setEmployeeEmail(string $employeeEmail): void {
        $this->employeeEmail = $employeeEmail;
    }

    /**
     * Retrieves the employee password.
     *
     * @return string The employee password.
     */
    public function getEmployeePassword(): string {
        return $this->employeePassword;
    }

    /**
     * Sets the employee password.
     *
     * @param string $employeePassword The new employee password.
     */
    public function setEmployeePassword(string $employeePassword): void {
        $this->employeePassword = $employeePassword;
    }

    /**
     * Retrieves the employee role.
     *
     * @return string The employee role.
     */
    public function getEmployeeRole(): string {
        return $this->employeeRole;
    }

    /**
     * Sets the employee role.
     *
     * @param string $employeeRole The new employee role.
     */
    public function setEmployeeRole(string $employeeRole): void {
        $this->employeeRole = $employeeRole;
    }

    /**
     * Retrieves the store associated with this employee.
     *
     * @return Stores The store associated with this employee.
     */
    public function getStore(): Stores {
        return $this->store;
    }

    /**
     * Sets the store associated with this employee.
     *
     * @param Stores $store The new store associated with this employee.
     */
    public function setStore(Stores $store): void {
        $this->store = $store;
    }
}
