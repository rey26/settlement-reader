<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Settlement;

class SettlementTest extends TestCase
{
    public function testSetAndGetAttributes()
    {
        $settlement = new Settlement();

        $settlement->setName('Test Name');
        $this->assertEquals('Test Name', $settlement->getName());

        $settlement->setMayorName('Test Mayor');
        $this->assertEquals('Test Mayor', $settlement->getMayorName());

        $settlement->setCityHallAddress('Test Address');
        $this->assertEquals('Test Address', $settlement->getCityHallAddress());

        $settlement->setPhone('123456789');
        $this->assertEquals('123456789', $settlement->getPhone());

        $settlement->setFax('987654321');
        $this->assertEquals('987654321', $settlement->getFax());

        $settlement->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $settlement->getEmail());

        $settlement->setCoatOfArmsPath('coat_of_arms.jpg');
        $this->assertEquals('coat_of_arms.jpg', $settlement->getCoatOfArmsPath());

        $settlement->setCoatOfArmsPathRemote('http://example.com');
        $this->assertEquals('http://example.com', $settlement->getCoatOfArmsPathRemote());

        $settlement->setWebAddress('http://example.com');
        $this->assertEquals('http://example.com', $settlement->getWebAddress());
    }

    public function testAddRemoveChildSettlement()
    {
        $parentSettlement = new Settlement();
        $childSettlement = new Settlement();

        $parentSettlement->addChildSettlement($childSettlement);

        $this->assertTrue($parentSettlement->getChildSettlements()->contains($childSettlement));
        $this->assertEquals($parentSettlement, $childSettlement->getParent());

        $parentSettlement->removeChildSettlement($childSettlement);

        $this->assertFalse($parentSettlement->getChildSettlements()->contains($childSettlement));
        $this->assertNull($childSettlement->getParent());
    }

    public function testToString()
    {
        $settlement = new Settlement();
        $settlement->setName('Test Name');

        $this->assertEquals('Test Name', (string) $settlement);
    }

    public function testGetId()
    {
        $settlement = new Settlement();
        $this->assertNull($settlement->getId());
    }
}
