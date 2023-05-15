<?php
namespace Wexo\HeyLoyalty\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wexo\HeyLoyalty\Api\GeneratePurchaseHistoryInterface;

class GeneratePurchaseHistory extends Command
{
    /**
     * @param GeneratePurchaseHistoryInterface $purchaseHistory
     * @param $name
     */
    public function __construct(
        public GeneratePurchaseHistoryInterface $purchaseHistory,
        $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('heyloyalty:purchase_history:generate');
        $this->setDescription('Generate Purchase History table for CSV consumption');

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
