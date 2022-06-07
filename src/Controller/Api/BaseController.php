<?php

namespace App\Controller\Api;

use App\Service\Factory\ApiResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;

class BaseController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected ApiResponseFactory $apiResponseFactory;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Required]
    public function setApiResponseFactory(ApiResponseFactory $apiResponseFactory): void
    {
        $this->apiResponseFactory = $apiResponseFactory;
    }
}