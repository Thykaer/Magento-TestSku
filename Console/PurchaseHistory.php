<?php
namespace Wexo\HeyLoyalty\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyPurchaseHistoryInterface;

class PurchaseHistory extends Command
{
    /**
     * @param HeyLoyaltyPurchaseHistoryInterface $purchaseHistory
     * @param $name
     */
    public function __construct(
        public HeyLoyaltyPurchaseHistoryInterface $purchaseHistory,
        $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('heyloyalty:purchasehistory');
        $this->setDescription('Get the HeyLoyalty purchase history.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->purchaseHistory->execute();
    }
}
