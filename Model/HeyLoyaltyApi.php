<?php

namespace Wexo\HeyLoyalty\Model;

use Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyClientInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class HeyLoyaltyApi implements HeyLoyaltyApiInterface
{
    /**
     * @param HeyLoyaltyConfigInterface $config
     * @param HeyLoyaltyClientInterface $client
     */
    public function __construct(
        public HeyLoyaltyConfigInterface $config,
        public HeyLoyaltyClientInterface $client
    ) {
    }

    /**
     * Get if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * Get lists from client
     *
     * @return array
     */
    public function getLists(): array
    {
        return $this->client->fetchLists();
    }
}
