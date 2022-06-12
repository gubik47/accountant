<?php

namespace App\Repository;

use App\Entity\Bank;
use Doctrine\ORM\EntityRepository;

/**
 * @method Bank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bank[]    findAll()
 * @method Bank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends EntityRepository<Bank>
 */
class BankRepository extends EntityRepository
{
    public function bankAlreadyExists(string $name): bool
    {
        $bank = $this->findOneBy(["name" => $name]);

        return boolval($bank);
    }
}
