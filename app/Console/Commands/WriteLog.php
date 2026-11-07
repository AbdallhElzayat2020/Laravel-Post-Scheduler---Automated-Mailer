<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:write-log')]
#[Description('This Command for write in Log')]
class WriteLog extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('This is a log message from the WriteLog command at' . Carbon::now());

    }
}
