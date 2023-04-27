<?php

namespace Wexo\HeyLoyalty\Model;

use Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyClientInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class HeyLoyaltyApi implements HeyLoyaltyApiInterface
{
    public function __construct(
        public HeyLoyaltyConfigInterface $config,
        public HeyLoyaltyClientInterface $client
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    public function getLists(): array
    {
        return $this->client->fetchLists();
    }
}
