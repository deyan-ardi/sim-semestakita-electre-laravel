<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Config;
use App\Models\TagihanIuran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateTagihanIuran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagihan:regenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $tagihan = TagihanIuran::where('due_date', '<', date('Y-m-d'))->where('status', 'UNPAID')->get();
            if ($tagihan->count() > 0) {
                $denda = Config::where('key', 'denda')->where('status', 'active')->first();
                if ($denda != '') {
                    $total_denda = $denda->value;
                } else {
                    $total_denda = 0;
                }
                foreach ($tagihan as $t) {
                    $t->status = 'OVERDUE';
                    $t->sub_total_denda = $total_denda;
                    $t->total_tagihan = $t->sub_total + $total_denda;
                    $t->save();
                }
            }
            DB::commit();
            return  $this->info('Tagihan sukses diregenerate');
        } catch (Exception $e) {
            DB::rollback();
            return  $this->info('Internal Server Error:' . $e);
        }
    }
}
