<?php

namespace Wexo\HeyLoyalty\Api;

interface HeyLoyaltyConfigInterface
{
    public function isEnabled(): bool;

    public function getApiKey(): string;

    public function getApiSecret(): string;
}
