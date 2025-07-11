<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $symptoms = [
            ['name' => 'Dismenorea', 'category' => 'Fisik'],
            ['name' => 'Mood Swing', 'category' => 'Mood'],
            ['name' => '5L', 'category' => 'Fisik', 'description' => 'Lemah, Letih, Lesu, Lemas, Lunglai'],
            ['name' => 'Anemia', 'category' => 'Fisik'],
            ['name' => 'Kram Perut', 'category' => 'Fisik'],
            ['name' => 'Nyeri Otot', 'category' => 'Fisik'],
            ['name' => 'Sakit Kepala', 'category' => 'Fisik'],
            ['name' => 'Jerawat', 'category' => 'Fisik'],
            ['name' => 'Nafsu Makan Bertambah', 'category' => 'Lainnya'],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::firstOrCreate(['name' => $symptom['name']], $symptom);
        }
    }
}
