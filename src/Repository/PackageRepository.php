<?php

namespace App\Repository;

use App\Dto\PackageSearchFilter;
use App\Entity\Business;
use App\Entity\Package;
use App\Enum\PackageStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @extends ServiceEntityRepository<Package>
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    public function findByFilter(PackageSearchFilter $filter): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->leftJoin('p.business', 'b')
            ->addSelect('b')
            ->leftJoin('b.business_type', 'bt')
            ->addSelect('bt')
            ->andWhere('p.status = :availableStatus')
            ->setParameter('availableStatus', PackageStatus::AVAILABLE->value);

        if($filter->name) {
            $qb->andWhere('p.name LIKE :name')
               ->setParameter('name', '%' . $filter->name . '%');
        }
        if($filter->minPrice) {
            $qb->andWhere('p.price > :minPrice')
                ->setParameter('minPrice', $filter->minPrice);
        }
        if($filter->maxPrice) {
            $qb->andWhere('p.price < :maxPrice')
                ->setParameter('maxPrice', $filter->maxPrice);
        }
        if($filter->category) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $filter->category->getId());
        }
        if($filter->business) {
            $qb->andWhere('b.id = :business')
                ->setParameter('business', $filter->business->getId());
        }
        if($filter->businessType) {
            $qb->andWhere('bt.id = :businessType')
                ->setParameter('businessType', $filter->businessType->getId());
        }

        $packages = $qb->getQuery()->getResult();

        if ($filter->business instanceof Business) {
            $businessStats = $this->createQueryBuilder('avgPackage')
                ->select('COUNT(avgPackage.id) AS packageCount, AVG(avgPackage.price) AS averagePrice')
                ->andWhere('avgPackage.business = :business')
                ->setParameter('business', $filter->business)
                ->getQuery()
                ->getSingleResult();

            if ((int) $businessStats['packageCount'] > 0) {
                $mysteryBox = new Package();
                $mysteryBox->setName('Mystery Box');
                $mysteryBox->setDescription('Surprise');
                $mysteryBox->setPrice((float) $businessStats['averagePrice']);
                $mysteryBox->setPhoto('');
                $mysteryBox->setCreatedAt(new DateTimeImmutable());
                $mysteryBox->setCategory(null);
                $mysteryBox->setBusiness($filter->business);
                $mysteryBox->setStatus('available');
                $mysteryBox->isMysteryBox = true;

                $packages[] = $mysteryBox;
            }
        }

        return $packages;

    }

    public function findRandomAvailableByBusiness(Business $business): ?Package
    {
        $packages = $this->createQueryBuilder('p')
            ->where('p.business = :business')
            ->andWhere('p.status = :status')
            ->setParameter('business', $business)
            ->setParameter('status', PackageStatus::AVAILABLE->value)
            ->getQuery()
            ->getResult();

        if (empty($packages)) {
            return null;
        }

        return $packages[array_rand($packages)];
    }

//    /**
//     * @return Package[] Returns an array of Package objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Package
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
