<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOrCreateFromOauth(array $oauthData, string $provider): User
    {
        $user = $this->findOneBy([
            'oauthId' => $oauthData['id'],
            'oauthType' => $provider
        ]);

        if (!$user) {
            $user = new User();
            $user->setEmail($oauthData['email']);
            $user->setOauthId($oauthData['id']);
            $user->setOauthType($provider);
            $user->setRoles(['ROLE_USER']);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }

        return $user;
    }
}