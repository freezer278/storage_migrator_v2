<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileExistsException;
use League\Flysystem\MountManager;

/**
 * Class StorageFilesMover
 * @package App\Library\Services\Storage
 */
class StorageFilesMover
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $sourceDisk;
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $destinationDisk;
    /**
     * @var MountManager
     */
    private $mountManager;

    /**
     * StorageFilesMover constructor.
     * @param string $sourceDiskName
     * @param string $destinationDiskName
     */
    public function __construct(string $sourceDiskName, string $destinationDiskName)
    {
        $this->sourceDisk = Storage::disk($sourceDiskName);
        $this->destinationDisk = Storage::disk($destinationDiskName);
        $this->mountManager = new MountManager([
            'source' => $this->sourceDisk->getDriver(),
            'destination' => $this->destinationDisk->getDriver(),
        ]);
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     */
    public function moveDirectory(string $source, string $destination, array $config = []): void
    {
        $files = $this->sourceDisk->allFiles($source);

        foreach ($files as $file) {
            $destinationPath = $destination . str_replace_first($source, '', $file);

            $this->move($file, $destinationPath, $config);
        }
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     */
    public function copyDirectory(string $source, string $destination, array $config = []): void
    {
        $files = $this->sourceDisk->allFiles($source);

        foreach ($files as $file) {
            $destinationPath = $destination . str_replace_first($source, '', $file);

            $this->copy($file, $destinationPath, $config);
        }
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     */
    public function move(string $source, string $destination, array $config = []): void
    {
        if ($this->destinationDisk->exists($destination)) {
            $this->destinationDisk->delete($destination);
        }

        $this->mountManager->move('source://' . $source, 'destination://' . $destination, $config);
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     */
    public function copy(string $source, string $destination, array $config = []): void
    {
        if ($this->destinationDisk->exists($destination)) {
            $this->destinationDisk->delete($destination);
        }

        $this->mountManager->copy('source://' . $source, 'destination://' . $destination, $config);
    }
}
