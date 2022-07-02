<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnCorrectJson_WhenSerialized(): void
    {
        $expected = [
            "id" => 1,
            "first_name" => "Test",
            "last_name" => "Testovič",
            "total_balance" => 0
        ];

        $user = new User();
        $user->setId($expected["id"])
            ->setFirstName($expected["first_name"])
            ->setLastName($expected["last_name"]);

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($user));
    }
}
