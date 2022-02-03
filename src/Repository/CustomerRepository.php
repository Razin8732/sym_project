<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $manager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Customer::class);
        $this->manager = $manager;
    }

    public function saveCustomer(Customer $customer)
    {
        $this->manager->persist($customer);
        $this->manager->flush();
        return $customer;
    }

    public function updateCustomer(Customer $customer)
    {
        $this->manager->persist($customer);
        $this->manager->flush();
        return $customer;
    }

    public function removeCustomer(Customer $customer, $images_directory)
    {
        $imageFilename = $customer->getImage();
        if (!empty($imageFilename)) {
            $filesystem = new Filesystem();
            $path = $images_directory . '/' . $imageFilename;
            $filesystem->remove($path);
        }
        $this->manager->remove($customer);
        $this->manager->flush();
    }
    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
