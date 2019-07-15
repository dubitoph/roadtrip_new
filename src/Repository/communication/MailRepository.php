<?php

namespace App\Repository\communication;

use App\Entity\user\User;
use FontLib\TrueType\Collection;
use App\Entity\communication\Mail;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\user\Owner;

/**
 * @method Mail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mail[]    findAll()
 * @method Mail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mail::class);
    }

    /**
     * @return Mail[]
     */
    public function findMailsAboutAdvert($user)
    {
        return $this->createQueryBuilder('m')
                    ->andWhere('m.sender = :user')
                    ->setParameter('user', $user)
                    ->orderBy('m.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult()
        ;
    }

    /**
     * @return Int[]
     */
    public function notReadMessages(User $user = null, Owner $owner = null)
    {
        
        if($user)
        {

            $userThreads = $user->getThreads();

        }

        if($owner)
        {

            $ownerThreads = $owner->getThreads();

        }
        
        
        if ($user || $owner) 
        {
        
            $query = $this->createQueryBuilder('m')
                        ->select('COUNT(m.id) AS number')
                        ->andWhere('m.thread IN (:threads)')
                        ->andWhere('m.isRead IS null')
            ;

            if ($owner) 
            {

                $query->setParameter('threads', $owner->getThreads());
                
            }
            else 
            {            

                $query->setParameter('threads', $user->getThreads());

            }

            return $query->getQuery()
                        ->getResult()
            ;
        }
        else 
        {

            return null;

        }

    }
    

    /*
    public function findOneBySomeField($value): ?Mail
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
