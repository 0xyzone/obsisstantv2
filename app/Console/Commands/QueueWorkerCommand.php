<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QueueWorkerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:custom-worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the queue worker';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);
    }
}
