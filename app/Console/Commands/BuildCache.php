<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Http\Controllers\MemberController;
class BuildCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds up the cache';

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
     * @return mixed
     */
    public function handle()
    {
        MemberController::getMembersListCache();
        $this->info('Member list cache built (also cached each members # events attended).');


    }
}
