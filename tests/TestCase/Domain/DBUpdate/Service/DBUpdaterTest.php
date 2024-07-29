<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Service;

use App\Domain\DBUpdate\Data\DBUpdateData;
use App\Domain\DBUpdate\Files\Update0001;
use App\Domain\DBUpdate\Repository\DBUpdateRepository;
use App\Domain\DBUpdate\Repository\DBUpdateTable;
use App\Domain\DBUpdate\Service\DBUpdater;
use App\Support\ClassLoader;
use App\Support\FileCollector;
use App\Test\Fixture\DBUpdateFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class DBUpdaterTest extends TestCase
{
    /**
     * Test.
     *
     * @return void
     */
    public function testupdateFileAlreadyUpdated(): void
    {
        $filename = 'Update0001.php';
        $up_stub = $this->createMock(Update0001::class);
        $up_stub->expects($this->once())
                ->method('getData')
                ->willReturn(new DBUpdateData((new DBUpdateFixture())->record));
        $fc_stub = $this->createMock(FileCollector::class);
        $fc_stub->expects($this->once())
                ->method('collectFiles')
                ->willReturn([$filename]);
        $cl_stub = $this->createMock(ClassLoader::class);
        $cl_stub->expects($this->once())
                ->method('loadClass')
                ->willReturn($up_stub);
        $pdo_stub = $this->createMock(\PDO::class);
        $repo_stub = $this->createMock(DBUpdateRepository::class);
        $repo_stub->expects($this->once())
                ->method('existsVersion')
                ->willReturn(true);
        $table_stub = $this->createMock(DBUpdateTable::class);

        $dbupdater = new DBUpdater($fc_stub, $cl_stub, $pdo_stub, $repo_stub, $table_stub);
        $result = $dbupdater->doUpdate();
        $this->assertEquals(0, count($result));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testupdateFileUpdateError(): void
    {
        $filename = 'Update0001.php';
        $up_stub = $this->createMock(Update0001::class);
        $up_stub->expects($this->once())
                ->method('getData')
                ->willReturn(new DBUpdateData((new DBUpdateFixture())->record));
        $up_stub->expects($this->once())
                ->method('setConnection');
        $up_stub->expects($this->once())
                ->method('doUpdate')
                ->willReturn(false);
        $fc_stub = $this->createMock(FileCollector::class);
        $fc_stub->expects($this->once())
                ->method('collectFiles')
                ->willReturn([$filename]);
        $cl_stub = $this->createMock(ClassLoader::class);
        $cl_stub->expects($this->once())
                ->method('loadClass')
                ->willReturn($up_stub);
        $pdo_stub = $this->createMock(\PDO::class);
        $repo_stub = $this->createMock(DBUpdateRepository::class);
        $repo_stub->expects($this->once())
                ->method('existsVersion')
                ->willReturn(false);
        $table_stub = $this->createMock(DBUpdateTable::class);

        $dbupdater = new DBUpdater($fc_stub, $cl_stub, $pdo_stub, $repo_stub, $table_stub);
        $result = $dbupdater->doUpdate();
        $this->assertEquals(0, count($result));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testupdateFileWithCreateTable(): void
    {
        $filename = 'Update0001.php';
        $up_stub = $this->createMock(Update0001::class);
        $up_stub->expects($this->once())
                ->method('getData')
                ->willReturn(new DBUpdateData((new DBUpdateFixture())->record));
        $up_stub->expects($this->once())
                ->method('setConnection');
        $up_stub->expects($this->once())
                ->method('doUpdate')
                ->willReturn(true);
        $fc_stub = $this->createMock(FileCollector::class);
        $fc_stub->expects($this->once())
                ->method('collectFiles')
                ->willReturn([$filename]);
        $cl_stub = $this->createMock(ClassLoader::class);
        $cl_stub->expects($this->once())
                ->method('loadClass')
                ->willReturn($up_stub);
        $pdo_stub = $this->createMock(\PDO::class);
        $repo_stub = $this->createMock(DBUpdateRepository::class);
        $repo_stub->expects($this->once())
                ->method('existsVersion')
                ->willReturn(false);
        $repo_stub->expects($this->once())
                ->method('insert')
                ->willReturn(1);
        $table_stub = $this->createMock(DBUpdateTable::class);
        $table_stub->expects($this->once())
                ->method('exists')
                ->willReturn(false);
        $table_stub->expects($this->once())
                ->method('createTable');

        $dbupdater = new DBUpdater($fc_stub, $cl_stub, $pdo_stub, $repo_stub, $table_stub);
        $result = $dbupdater->doUpdate();
        $this->assertEquals(1, count($result));
    }
}
