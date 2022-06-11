<?php

namespace App\Repository;

use App\Entity\BankAccount;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * @method BankAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccount[]    findAll()
 * @method BankAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountRepository extends EntityRepository
{
    public function accountAlreadyExistsForUser(User $user, string $number): bool
    {
        $bank = $this->findOneBy(["number" => $number, "user" => $user]);

        return boolval($bank);
    }
}
