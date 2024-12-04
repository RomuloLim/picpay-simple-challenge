<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class playground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playground';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        function fodase()
        {
            return [
                'fodase1',
                'fodasadwadawdae2',
            ];
        }

        [$fodase1, $fodase2] = fodase();

        dd($fodase1, $fodase2);
    }
}
