<?php

namespace Wexo\HeyLoyalty\Controller\PurchaseHistory;

use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;

class CsvExport implements HttpGetActionInterface
{
    public Filesystem\Directory\WriteInterface $directory;

    /**
     * @throws FileSystemException
     */
    public function __construct(
        public Filesystem $filesystem,
        public FileFactory $fileFactory,
        public ResourceConnection $connection
    ) {
        $this->directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * Create CSV export file from table and return it
     *
     * @return ResponseInterface
     * @throws FileSystemException
     * @throws Exception
     */
    public function execute(): ResponseInterface
    {
        $connection = $this->connection->getConnection();
        $table = $connection->getTableName('heyloyalty_export');
        $query = "SELECT * {$table}";
        $data = $connection->fetchAll($query);

        $path = 'heyloyalty/export.csv';
        $fileName = 'Export.csv';
        $this->directory->create('heyloyalty');
        $stream = $this->directory->openFile($path, 'w+');
        $stream->lock();

        $data = [
            [
                'sku' => '1',
                'name' => 'Test1',
                'price' => 100
            ],
            [
                'sku' => '2',
                'name' => 'Test2',
                'price' => 200
            ],
            [
                'sku' => '3',
                'name' => 'Test3',
                'price' => 50
            ]
        ];

        foreach ($data as $product) {
            $line = [];
            $line[] = $product['sku'];
            $line[] = $product['name'];
            $line[] = $product['price'];
            $stream->writeCsv($line);
        }

        $content['type'] = 'filename';
        $content['value'] = $path;
        return $this->fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
