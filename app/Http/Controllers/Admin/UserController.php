<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jumlahUser = User::whereIn('role', [1, 2, 3, 6])->count();
        $superAdmin = User::where('role', 1)->count();
        $pengelola = User::where('role', 2)->count();
        $pegawai = User::where('role', 3)->count();
        $guest = User::where('role', 6)->count();
        $users = User::whereIn('role', [1, 2, 3, 6])->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'UserController.php',
            'action' => 'Halaman Awal User',
        ]);
        // End Log
        return view('admin.page.user.index', compact(['users', 'guest', 'jumlahUser', 'superAdmin', 'pengelola', 'pegawai']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'UserController.php',
            'action' => 'Form Tambah User',
        ]);
        // End Log
        return view('admin.page.user.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['required', 'string', 'min:8', 'required_with:password', 'same:password'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
            'no_telp' => ['required', 'unique:users'],
            'role' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $user =  User::create([
                'id' => Uuid::uuid4(),
                'no_telp' => $request->no_telp,
                'name' => ucWords($request->name),
                'email' => strtolower($request->email),
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'UserController.php',
                'action' => 'Store User',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'UserController.php',
            'action' => 'Form Ubah User',
        ]);
        // End Log
        return view('admin.page.user.edit', compact(['user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // Check User if email in db == email in input form
        if ($user->email == $request->email) {
            $valid_email =  ['required', 'email'];
        } else {
            $valid_email =  ['required', 'email', 'unique:users'];
        }

        // Check User if no_telp in db == no_telp in input form
        if ($user->no_telp == $request->no_telp) {
            $valid_no_telp =  ['required', 'max:15'];
        } else {
            $valid_no_telp =  ['required', 'unique:users', 'max:15'];
        }
        $request->validate([
            'password' => ['nullable', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['nullable', 'string', 'min:8', 'required_with:password', 'same:password'],
            'name' => ['required', 'string', 'max:100'],
            'email' => $valid_email,
            'no_telp' => $valid_no_telp,
            'role' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }

            $user->no_telp = $request->no_telp;
            $user->name = ucWords($request->name);
            $user->email = strtolower($request->email);
            $user->role = $request->role;
            $user->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'UserController.php',
                'action' => 'Update User',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if ($user->id == Auth::user()->id) {
                return redirect(route('user'))->with('error', 'Tidak Dapat Menghapus Diri Sendiri');
            }

            if ($user->role == 1) {
                return redirect(route('user'))->with('error', 'Tidak Dapat Menghapus Super Admin');
            }

            $user->delete();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'UserController.php',
                'action' => 'Hapus User',
            ]);
            // End Log
            DB::commit();
            return redirect(route('user'))->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
