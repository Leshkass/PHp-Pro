<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute as Serializer;



#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['order_item'])]
    private int $id;

    #[ORM\Column(name: 'vin_code',type: 'string')]
    #[Serializer\Groups(['order_item'])]
    private string $vinCode;

    #[ORM\Column(name: 'order_price', type: 'integer')]
    #[Serializer\Groups(['order_item'])]
    private int $orderPrice;

    #[ORM\JoinColumn(name: 'car_id')]
    #[ORM\ManyToOne(targetEntity: Car::class,inversedBy: 'order')]
    private Car $car;

    #[ORM\JoinColumn(name: 'client_id')]
    #[ORM\ManyToOne(targetEntity: Client::class,inversedBy: 'order')]
    private Client $client;

    #[ORM\OneToMany(targetEntity: OrderWork::class, mappedBy: 'order')]
    private Collection $orderWorks;

    public function __construct()
    {
        $this->orderWorks = new ArrayCollection();
    }

    public function getVinCode(): string
    {
        return $this->vinCode;
    }

    public function setVinCode(string $vinCode): void
    {
        $this->vinCode = $vinCode;
    }

    public function getOrderPrice(): int
    {
        return $this->orderPrice;
    }

    public function setOrderPrice(int $orderPrice): void
    {
        $this->orderPrice = $orderPrice;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function setCar(Car $car): void
    {
        $this->car = $car;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return Collection|OrderWork[]
     */
    public function getOrderWorks(): Collection
    {
        return $this->orderWorks;
    }

    public function addOrderWork(OrderWork $orderWork): void
    {
        if(!$this->orderWorks->contains($orderWork)) {
            $this->orderWorks->add($orderWork);
        }

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