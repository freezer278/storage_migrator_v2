<?php

namespace App\Jobs;

use App\Utils\StorageFilesMover;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateSingleFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $file;

    /**
     * Create a new job instance.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [$this->file];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filesMover = new StorageFilesMover('source', 'destination');
        $filesMover->copy($this->file, $this->file);
    }
}
