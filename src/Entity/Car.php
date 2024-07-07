<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enum\BodyType;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute as Serializer;


#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Table(name: 'car')]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['car_item'])]
    private int $id;

    #[Assert\Length(min: 3, minMessage: ' Brand must be longer than {{ limit }}characters')]
    #[ORM\Column(name: 'brand', type: 'string', length: 150)]
    #[Serializer\Groups(['car_item'])]
    private string $brand;

    #[ORM\Column(name: 'model', type: 'string', length: 50)]
    #[Serializer\Groups(['car_item'])]
    private string $model;

    #[ORM\Column(name: 'year', type: 'integer')]
    #[Serializer\Groups(['car_item'])]
    private int $year;

    #[Assert\Length(min: 3, minMessage: 'Color must be longer than {{ limit }}characters')]
    #[ORM\Column(name: 'color', type: 'string', length: 50, nullable: true)]
    private ?string $color;

    #[ORM\Column(name: 'body_type', nullable: true, enumType: BodyType::class)]
    private ?BodyType $bodyType;

    #[ORM\OneToMany(targetEntity: Order::class,mappedBy: 'car')]
    private Collection $orders;


    #[ORM\ManyToMany(targetEntity: Client::class, mappedBy: 'cars', cascade: ["persist"])]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function addClient(Client $client): void
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->addCar($this);
        }
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    public function getBodyType(): ?BodyType
    {
        return $this->bodyType;
    }

    public function setBodyType(?BodyType $bodyType): void
    {
        $this->bodyType = $bodyType;
    }

    public function getFullInfo(): array
    {
        return [
            'Brand' => $this->getBrand(),
            'Model' => $this->getModel(),
            'Year' => $this->getYear(),
            'Color' => $this->getColor(),
            'BodyType' => $this->getBodyType()->value
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
    public function getFullName(): string
    {
        return $this->brand . '  ' . $this->model . '  ' . $this->year;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}