<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Exceptions\InvalidName;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'client')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'first_name', type: 'string', length: 50)]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 50)]
    private string $lastName;

    #[ORM\Column(name: 'email', type: 'string', length: 150)]
    private string $email;

    #[ORM\OneToMany(targetEntity: Order::class,mappedBy: 'client')]
    private Collection $orders;

    #[ORM\JoinTable(name: 'cars_client')]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'car_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Car::class, inversedBy: 'clients')]
    private Collection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): void
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->addClient($this);
        }
    }

    /**
     * @throws InvalidName
     */
    public function setFirstName(string $firstName): void
    {
        if (strlen($firstName) <= 2) {
            throw new InvalidName('First name must be at least 2 characters long');
        }
        $this->firstName = $firstName;
    }

    public function setLastName(string $surName): void
    {
        $this->lastName = $surName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullInfo(): array
    {
        return [
            'Full name' => $this->getFullName(),
            'Email' => $this->getEmail()
        ];
    }

    public function getOrder(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): void
    {
        if(!$this->orders->contains($order)) {
            $this->orders->add($order);
        }
    }

    public function removeCar(Car $car): void
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
        }
    }
}
