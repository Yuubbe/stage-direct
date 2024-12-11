<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($registry, User::class);
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Utilisé pour mettre à jour (rehacher) le mot de passe de l'utilisateur au fil du temps.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newPlainPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        // Hachage du nouveau mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPlainPassword);

        // Mise à jour du mot de passe de l'utilisateur
        $user->setPassword($hashedPassword);

        // Sauvegarde de l'utilisateur avec le mot de passe mis à jour
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // Exemple de méthode pour trouver un utilisateur par son email
    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // Vous pouvez ajouter des méthodes supplémentaires pour interagir avec la base de données selon vos besoins.
}
