<?php

namespace App\Tests\Unit\Service\RequestValidator;

use App\Exception\ApiException;
use App\Service\RequestValidator\BaseRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class BaseRequestValidatorTest extends TestCase
{
    private BaseRequestValidator $validator;

    public function setUp(): void
    {
        $this->validator = $this->getMockForAbstractClass(BaseRequestValidator::class);
    }

    /**
     * @test
     */
    public function Should_ThrowAnException_WhenInvalidContentType(): void
    {
        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "text/plain"]);

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);
    }

    /**
     * @test
     */
    public function Should_ThrowAnException_WhenEmptyRequestBody(): void
    {
        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"]);

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);
    }

    /**
     * @test
     */
    public function Should_ThrowAnException_WhenNonJSONRequestBody(): void
    {
        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], "test");

        $this->expectException(ApiException::class);

        $this->validator->validateRequest($request);
    }

    /**
     * @test
     */
    public function Should_Not_Throw_An_Exception_On_Valid_Request(): void
    {
        $request = new Request([], [], [], [], [], ["CONTENT_TYPE" => "application/json"], "{\"test\": \"test\"}");

        $this->expectNotToPerformAssertions();

        $this->validator->validateRequest($request);
    }
}
