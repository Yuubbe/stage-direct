<?php
// src/Security/SupabaseUser.php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class SupabaseUser implements UserInterface
{
    private $id;
    private $email;
    private $roles;
    private $isVerified;

    public function __construct(string $id, string $email, array $roles, bool $isVerified)
    {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
        $this->isVerified = $isVerified;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // Si vous stockez des mots de passe en clair, effacez-les ici
    }

    // Ajoutez d'autres getters si nécessaire
}