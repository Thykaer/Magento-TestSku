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

    /**
     * Get a list from client
     *
     * @param int $id
     * @return array
     */
    public function getList(int $id): array
    {
        return $this->client->fetchList($id);
    }

    /**
     * Get from config if tracking is activated
     *
     * @return bool
     */
    public function getIsTrackingActivated(): bool
    {
        return $this->config->getIsTrackingActivated();
    }

    /**
     * Get tracking id from config
     *
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->config->getTrackingId();
    }
}
