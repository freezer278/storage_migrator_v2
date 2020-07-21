<?php

namespace App\Console\Commands;

use App\Jobs\MigrateSingleFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class SyncStorages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync files from storage 1 to storage 2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Getting files in source storage');
        $filesInSourceStorage = Storage::disk('source')->allFiles('/');
        $this->info('got files in source storage: ' . count($filesInSourceStorage));

        $this->info('Getting files in destination storage');
        $filesInDestinationStorage = Storage::disk('destination')->allFiles('/');
        $this->info('got files in destination storage: ' . count($filesInDestinationStorage));

        $files = array_diff($filesInSourceStorage, $filesInDestinationStorage);
        unset($filesInSourceStorage, $filesInDestinationStorage);

        $totalFiles = count($files);

        $this->info('files number to sync: ' . $totalFiles);
        $this->info('');
        $this->info('');
        $this->info('');

        $output = new ConsoleOutput();
        $bar = new ProgressBar($output, $totalFiles);

        foreach ($files as $file) {
            $this->info($file);
            MigrateSingleFile::dispatch($file);
            $bar->advance();
        }

        $bar->finish();

        echo PHP_EOL;
    }
}
