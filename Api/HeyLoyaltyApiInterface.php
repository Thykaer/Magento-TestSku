<?php namespace Wexo\HeyLoyalty\Api;

interface HeyLoyaltyApiInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return array
     */
    public function getLists(): array;
}
