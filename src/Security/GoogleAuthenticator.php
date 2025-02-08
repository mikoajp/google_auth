<?php
namespace App\Security;

use League\OAuth2\Client\Provider\GoogleUser;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected function getProviderName(): string
    {
        return 'google';
    }

    protected function getOAuthUserData($owner): array
    {
        /** @var GoogleUser $owner */
        return [
            'id' => $owner->getId(),
            'email' => $owner->getEmail()
        ];
    }
}