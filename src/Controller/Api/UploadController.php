<?php

namespace App\Controller\Api;

use App\Entity\BankAccount;
use App\Service\Parser\TransactionImporter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends BaseController
{

}