<?php

namespace App\Console\Commands;

use Exception;
use App\Models\LogActivity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DestroyLogActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Destroy Log Activity In Log Activity Table';

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
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $log = LogActivity::all();
            foreach ($log as $log) {
                $log->delete();
            }
            DB::commit();
            return $this->info('Log Activity Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return $this->info('Internal Server Error:' . $e);
        }
    }
}
