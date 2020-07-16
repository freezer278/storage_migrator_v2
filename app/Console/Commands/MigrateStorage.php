<?php

namespace App\Console\Commands;

use App\Jobs\MigrateSingleFile;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class MigrateStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate from source to destination cloud storage';

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
        $files = Storage::disk('source')->allFiles('/');

        $totalFiles = count($files);

        $output = new ConsoleOutput();
        $bar = new ProgressBar($output, $totalFiles);

        foreach ($files as $file) {
            MigrateSingleFile::dispatch($file);
            $bar->advance();
        }

        $bar->finish();

        echo PHP_EOL;
    }
}
