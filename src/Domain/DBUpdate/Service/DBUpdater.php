<?php

declare(strict_types = 1);

namespace App\Domain\DBUpdate\Service;

use App\Domain\DBUpdate\Repository\DBUpdateRepository;
use App\Domain\DBUpdate\Repository\DBUpdateTable;
use App\Support\ClassLoader;
use App\Support\FileCollector;

/**
 * DB updater.
 */
class DBUpdater
{
    public const UPDATE_DIR = '/Files';

    private const NAMESPACE = '\App\Domain\DBUpdate\Files\\';

    private const FILE_MASK = '/^Update\d{4}.php$/';

    private FileCollector $filecollector;

    private ClassLoader $classloader;

    private \PDO $pdo;

    private DBUpdateRepository $repo;

    private DBUpdateTable $table;

    private string $directory;

    /**
     * The constructor.
     *
     * @param FileCollector $filecollector The file collector
     * @param ClassLoader $classloader The class loader
     * @param \PDO $pdo The connection
     * @param DBUpdateRepository $repo The repository
     * @param DBUpdateTable $table The table repository
     */
    public function __construct(
        FileCollector $filecollector,
        ClassLoader $classloader,
        \PDO $pdo,
        DBUpdateRepository $repo,
        DBUpdateTable $table
    ) {
        $this->filecollector = $filecollector;
        $this->classloader = $classloader;
        $this->pdo = $pdo;
        $this->repo = $repo;
        $this->table = $table;
        $this->directory = dirname(__DIR__) . self::UPDATE_DIR;
    }

    /**
     * Do update the all file.
     *
     * @return array Of the updated files
     */
    public function doUpdate(): array
    {
        $updated = [];
        try {
            $files = $this->filecollector->collectFiles($this->directory, self::FILE_MASK);
            sort($files, SORT_STRING);
            foreach ($files as &$filename) {
                if ($this->updateFile($filename)) {
                    $updated[] = $filename;
                }
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc->getMessage());
        }

        return $updated;
    }

    /**
     * Update file.
     *
     * @param string $filename The filename
     *
     * @return bool If updated
     */
    private function updateFile(string $filename): bool
    {
        $updater = $this->classloader->loadClass($this->directory, self::NAMESPACE, basename($filename, '.php'));

        $data = $updater->getData();
        if ($this->repo->existsVersion($data->version)) {
            return false;
        }

        $updater->setConnection($this->pdo);
        if ($updater->doUpdate()) {
            if (!$this->table->exists()) {
                $this->table->createTable();
            }
            $this->repo->insert($data);

            return true;
        }

        return false;
    }
}
