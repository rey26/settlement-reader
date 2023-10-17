<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Settlement;

class SettlementTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $settlement = new Settlement();

        $settlement->setName('Example Town');
        $settlement->setMayorName('John Mayor');
        $settlement->setCityHallAddress('123 Main St');
        $settlement->setPhone('555-123-4567');
        $settlement->setFax('555-987-6543');
        $settlement->setEmail('example@example.com');
        $settlement->setCoatOfArmsPath('/path/to/coat_of_arms.png');
        $settlement->setWebAddress('http://exampletown.com');

        $this->assertEquals('Example Town', $settlement->getName());
        $this->assertEquals('John Mayor', $settlement->getMayorName());
        $this->assertEquals('123 Main St', $settlement->getCityHallAddress());
        $this->assertEquals('555-123-4567', $settlement->getPhone());
        $this->assertEquals('555-987-6543', $settlement->getFax());
        $this->assertEquals('example@example.com', $settlement->getEmail());
        $this->assertEquals('/path/to/coat_of_arms.png', $settlement->getCoatOfArmsPath());
        $this->assertEquals('http://exampletown.com', $settlement->getWebAddress());
    }

    public function testChildSettlements(): void
    {
        $settlement = new Settlement();
        $childSettlement = new Settlement();

        $settlement->addChildSettlement($childSettlement);

        $this->assertTrue($settlement->getChildSettlements()->contains($childSettlement));

        $settlement->removeChildSettlement($childSettlement);

        $this->assertFalse($settlement->getChildSettlements()->contains($childSettlement));
        $this->assertNull($childSettlement->getParent());
    }
}
