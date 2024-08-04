<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Enum\Type;
use App\Entity\Oil;
use App\Entity\Order;
use App\Entity\OrderWork;
use App\Entity\Spare;
use App\Service\ServiceCostCalculator;
use PHPUnit\Framework\TestCase;


class ServiceCostCalculateTest extends TestCase
{
    public function calculateOrderTotalCostProvider(): array
    {
        return [
            [
                322,
                [Type::Tinting->value => 50, Type::Change_Oil->value => 88],
                [25, 67],
                []
            ],
            [
                518,
                [Type::Tire_Replacement->value => 99, Type::Change_Oil->value => 77],
                [],
                [78, 93]
            ],
        ];
    }

    /**
     * @dataProvider calculateOrderTotalCostProvider
     */
    public function testTotalCostCalculation(int $expectedTotalPrice, array $orderWorks, array $sparePrices, array $consumablePrices): void
    {
        $order = new Order();

        foreach ($orderWorks as $type => $workCost) {
            $orderWork = new OrderWork();
            $orderWork->setType(Type::from($type));
            $orderWork->setCostOfWork($workCost);

            foreach ($sparePrices as $sparePrice) {
                $spare = new Spare();
                $spare->setPrice($sparePrice);

                $orderWork->addSpare($spare);
            }


            foreach ($consumablePrices as $consumablePrice) {
                $consumable = new Oil();
                $consumable->setPrice($consumablePrice);

                $orderWork->addConsumable($consumable);
            }

            $order->addOrderWork($orderWork);
        }

        $costCalculator = new ServiceCostCalculator();
        $totalPrice = $costCalculator->calculateTotalCost($order);

        $this->assertEquals($expectedTotalPrice, $totalPrice);
    }
}