<?php

namespace App\Repository;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends EntityRepository
{
    const DEFAULT_ITEMS_PER_PAGE = 20;

    public function getTransactionList(BankAccount $account, Request $request): array
    {
        $qb = $this->getTransactionListQueryBuilder($account);

        $count = $qb->select("COUNT(t)")
            ->getQuery()
            ->getSingleScalarResult();

        list ($sort, $limit, $offset) = $this->parseTransactionListRequestParams($request);

        $transactions = $qb->select("t")
            ->orderBy(...$sort)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        return [$transactions, $count];
    }

    private function getTransactionListQueryBuilder(BankAccount $account): QueryBuilder
    {
        return $this->createQueryBuilder("t")
            ->andWhere("t.bankAccount = :account")
            ->setParameters(["account" => $account]);
    }

    private function parseTransactionListRequestParams(Request $request): array
    {
        $limit = $request->query->get("limit");
        if (!is_int($limit)) {
            $limit = TransactionRepository::DEFAULT_ITEMS_PER_PAGE;
        }

        $page = $request->query->get("page");
        if (!$page || !is_scalar($page)) {
            $page = 1;
        } else {
            $page = intval($page);
        }

        $offset = $limit * ($page - 1);

        // only default sorting for now
        $sort = ["t.dateOfIssue", "DESC"];

        return [$sort, $limit, $offset];
    }
}
