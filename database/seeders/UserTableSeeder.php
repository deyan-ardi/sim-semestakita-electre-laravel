<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PengangkutanPenilaianHarian;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 4; $i++) {
            if ($i == 1) {
                $role = 1;
                $email = 'super.admin@tridatu.id';
            } elseif ($i == 2) {
                $role = 2;
                $email = 'pengelola@tridatu.id';
            } elseif ($i == 3) {
                $role = 3;
                $email = 'pegawai@tridatu.id';
            } elseif ($i == 4) {
                $role = 6;
                $email = 'tamu@tridatu.id';
            }
            $user = new User();
            $user->id = Uuid::uuid4();
            $user->name = $faker->name;
            $user->email = $email;
            $user->password = Hash::make('12345678');
            $user->password_whatsapp = Hash::make('12345678');
            // $user->no_telp = '081234' . random_int(100000, 999999);
            $user->role = $role;
            $user->save();
        }

        $dataPengangkutan = PengangkutanPenilaianHarian::all();
        foreach ($dataPengangkutan as $item) {
            $item->pegawai_id = User::where('role', 3)->first()->id;
            $item->save();
        }
    }
}
