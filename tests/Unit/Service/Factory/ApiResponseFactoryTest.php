<?php

namespace App\Tests\Unit\Service\Factory;

use App\Component\ApiResponse;
use App\Service\Factory\ApiResponseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseFactoryTest extends TestCase
{
    private ApiResponseFactory $factory;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->factory = new ApiResponseFactory();
    }

    public function testApiResponseCreation(): void
    {
        $response = $this->factory->createSuccessResponseMessage("Success");

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals("application/json", $response->headers->get("Content-type"));

        $body = json_decode($response->getContent(), true);

        $this->assertEquals("Success", $body["message"]);
    }

    public function testSuccessApiResponseCreation(): void
    {
        $response = $this->factory->createSuccessResponseMessage("Success");

        $body = json_decode($response->getContent(), true);

        $this->assertEquals(ApiResponse::STATUS_SUCCESS, $body["status"]);
    }

    public function testErrorApiResponseCreation(): void
    {
        $response = $this->factory->createErrorResponseMessage("Error");

        $body = json_decode($response->getContent(), true);

        $this->assertEquals(ApiResponse::STATUS_ERROR, $body["status"]);
    }

    public function testEmptyMessageResponseCreation(): void
    {
        $response = $this->factory->createErrorResponseMessage("");

        $body = json_decode($response->getContent(), true);

        $this->assertEquals("", $body["message"]);
    }
}
