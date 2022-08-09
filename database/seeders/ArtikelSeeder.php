<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use App\Models\Artikel;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++) {
            Artikel::create([
                'id' => Uuid::uuid4(),
                'judul' => 'Tips Mencari Jodoh Ala Jomblo',
                'slug' => 'tips-mencari-jodoh-ala-jomblo',
                'gambar' => 'artikel/gambar-jodoh.jpg',
                'kategori' => 'artikel',
                'konten' => 'mantap jiwa',
                'created_by' => 'Admin',
                'updated_by' => 'Admin',
            ]);
        }
    }
}
