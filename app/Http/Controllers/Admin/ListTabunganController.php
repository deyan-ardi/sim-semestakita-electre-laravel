<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Tabungan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Exports\TabunganExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TabunganSingleExport;
use Illuminate\Support\Facades\Validator;

class ListTabunganController extends Controller
{
    public function __construct()
    {
        // Delete All Tmp When Go To This Controller
        $this->middleware(function ($request, $next) {
            $this->removeSessionTmpAll();
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }

    public function index()
    {
        $tabungan = Tabungan::select('tabungan.*', 'users.id as user_id')->join('users', 'users.id', '=', 'tabungan.user_id')->where('users.role', 4)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ListTabunganController.php',
            'action' => 'Halaman Awal List Tabungan',
        ]);
        // End Log
        return view('admin.page.list-tabungan.index', ['tabungan' => $tabungan]);
    }

    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'after:tanggal_awal'],
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('list-tabungan'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }
        $tabungan = Tabungan::select('tabungan.*', 'users.id as user_id')->join('users', 'users.id', '=', 'tabungan.user_id')->where('users.role', 4)->where('tabungan.updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('tabungan.updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ListTabunganController.php',
            'action' => 'Filter List Tabungan',
        ]);
        // End Log
        return view('admin.page.list-tabungan.index', ['tabungan' => $tabungan]);
    }

    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tanggal_awal == null || $request->tanggal_akhir == null) {
                $data = Tabungan::select('users.no_member', 'users.name', 'kredit', 'debet', 'saldo', DB::raw("DATE_FORMAT(tabungan.updated_at, '%d-%b-%Y %H:%i') as format_date"))->join('users', 'users.id', '=', 'tabungan.user_id')->where('users.role', 4);
            } else {
                $data = Tabungan::select('users.no_member', 'users.name', 'kredit', 'debet', 'saldo', DB::raw("DATE_FORMAT(tabungan.updated_at, '%d-%b-%Y %H:%i') as format_date"))->join('users', 'users.id', '=', 'tabungan.user_id')->where('tabungan.updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('tabungan.updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->where('users.role', 4);
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ListTabunganController.php',
                'action' => 'Export Excel List Tabungan',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new TabunganExport($data), 'export_data_tabungan.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function export_single($id)
    {
        DB::beginTransaction();
        try {
            $data_export = Tabungan::select('users.no_member', 'users.name', 'kredit', 'debet', 'saldo', DB::raw("DATE_FORMAT(tabungan.updated_at, '%d-%b-%Y %H:%i') as format_date"))->join('users', 'users.id', '=', 'tabungan.user_id')->where('tabungan.id', $id);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ListTabunganController.php',
                'action' => 'Export Single Data List Tabungan',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new TabunganSingleExport($data_export), 'export_data_tabungan_single.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
