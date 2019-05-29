<?php

namespace App\Utils;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class StorageFilesMover
 * @package App\Library\Services\Storage
 */
class StorageFilesMover
{
    /**
     * @var Filesystem
     */
    private $sourceDisk;
    /**
     * @var Filesystem
     */
    private $destinationDisk;

    /**
     * StorageFilesMover constructor.
     * @param string $sourceDiskName
     * @param string $destinationDiskName
     */
    public function __construct(string $sourceDiskName, string $destinationDiskName)
    {
        $this->sourceDisk = Storage::disk($sourceDiskName);
        $this->destinationDisk = Storage::disk($destinationDiskName);
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

        try {
            $this->destinationDisk
                ->getDriver()
                ->writeStream(
                    $destination,
                    $this->sourceDisk->getDriver()->readStream($source)
                );

            $this->sourceDisk->delete($source);
        } catch (Exception $exception) {
            Log::debug('Exception during moving file ' . $source);
            Log::debug($exception->__toString());
            Log::debug($exception->getTraceAsString());
        }
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

        try {
            $this->destinationDisk
                ->getDriver()
                ->writeStream(
                    $destination,
                    $this->sourceDisk->getDriver()->readStream($source)
                );
        } catch (Exception $exception) {
            Log::debug('Exception during copying file ' . $source);
            Log::debug($exception->__toString());
            Log::debug($exception->getTraceAsString());
        }
    }
}
