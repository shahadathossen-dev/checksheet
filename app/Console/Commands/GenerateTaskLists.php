<?php

namespace App\Console\Commands;

use App\Enums\CheckSheetStatus;
use App\Enums\CheckSheetType;
use App\Models\CheckSheet;
use App\Models\TaskItem;
use App\Models\TaskList;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class GenerateTaskLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tasklists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will seed the permissions to db.';

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
        $checksheet = CheckSheet::find(10);
        print count($checksheet->checksheetItems);
        // print $checksheet->id;
    }
}
