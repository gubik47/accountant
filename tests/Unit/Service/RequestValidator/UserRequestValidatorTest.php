<?php

namespace App\Tests\Unit\Service\RequestValidator;

use App\Exception\ApiException;
use App\Service\RequestValidator\UserRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UserRequestValidatorTest extends TestCase
{
    private UserRequestValidator $validator;

    public function setUp(): void
    {
        $this->validator = new UserRequestValidator();
    }

    /**
     * @test
     */
    public function Should_ThrowAnException_WhenIncompleteRequestDataPassed(): void
    {
        $data = "{\"first_name\": \"Test\"}";

        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], $data);

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);

        $data = "{\"last_name\": \"Test\"}";

        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], $data);

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);
    }

    /**
     * @test
     */
    public function Should_ThrowAnException_WhenInvalidRequestDataPassed(): void
    {
        $data = "{\"first_name\": [{\"test\": \"x\"}], \"last_name\": \"y\"}";

        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], $data);

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);
    }

    /**
     * @test
     */
    public function ShouldNot_ThrowAnException_WhenValidRequestDataPassed(): void
    {
        $data = "{\"first_name\": \"Testovič\", \"last_name\": \"Test\"}";

        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], $data);

        $this->expectNotToPerformAssertions();

        $this->validator->validateRequest($request);
    }
}
