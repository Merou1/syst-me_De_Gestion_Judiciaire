<?php

namespace App\Repository;

use App\Entity\PreuvesDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreuvesDossier>
 *
 * @method PreuvesDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreuvesDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreuvesDossier[]    findAll()
 * @method PreuvesDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreuvesDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreuvesDossier::class);
    }
}
