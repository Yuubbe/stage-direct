<?php
// src/Security/SupabaseUserProvider.php
namespace App\Security;

use App\Service\SupabaseService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SupabaseUserProvider implements UserProviderInterface
{
    private $supabaseService;

    public function __construct(SupabaseService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $client = $this->supabaseService->getClient();
        
        try {
            $user = $client->auth()->getUser($identifier);
            
            // Créez et retournez un objet User Symfony à partir des données Supabase
            return new SupabaseUser(
                $user->id,
                $user->email,
                $user->user_metadata['roles'] ?? ['ROLE_USER'],
                $user->email_confirmed_at !== null
            );
        } catch (\Exception $e) {
            throw new UserNotFoundException();
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SupabaseUser) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return SupabaseUser::class === $class || is_subclass_of($class, SupabaseUser::class);
    }
}