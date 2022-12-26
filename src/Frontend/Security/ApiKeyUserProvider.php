<?php

declare(strict_types=1);

namespace App\Frontend\Security;

use Symfony\Component\Security\Core\User\InMemoryUserProvider;

class ApiKeyUserProvider extends InMemoryUserProvider
{
    /**
     * @param array<string> $apiKeys
     */
    public function __construct(array $apiKeys)
    {
        $users = [];

        foreach ($apiKeys as $apiKey) {
            $users[$apiKey] = [];
        }

        parent::__construct($users);
    }
}
