<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $symptoms = [
            [
                'name' => 'Dismenorea',
                'category' => 'Fisik',
                'description' => 'Nyeri atau kram hebat selama menstruasi.',
                'recommendation' => "Untuk menekan rasa sakit, cukup dilakukan kompres hangat, olahraga teratur, istirahat yang cukup, minum air kelapa hijau, minuman jahe, sereh, serta jamu kunir-asem, dan akupresur/ Sanyinjiao Hegu. https://youtu.be/l7Z91rEHD6w. Apabila nyeri haid yang dirasakan sampai mengganggu aktivitas sehari-hari, bisa diberikan obat anti peradangan yang bersifat non steroid atau berkonsultasi langsung dengan tenaga kesehatan."
            ],
            [
                'name' => 'Mood Swing',
                'category' => 'Mood',
                'description' => 'Perubahan suasana hati yang cepat.',
                'recommendation' => "Lakukan aroma terapi, meditasi, atau aktivitas relaksasi lainnya untuk membantu menstabilkan suasana hati."
            ],
            [
                'name' => '5L',
                'category' => 'Fisik',
                'description' => 'Lemah, Letih, Lesu, Lemas, Lunglai.',
                'recommendation' => "Untuk mencegah anemia, saat menstruasi, minumlah 1 tablet penambah darah (tablet Fe) selama menstruasi setiap hari dan sekali seminggu ketika tidak menstruasi."
            ],
            [
                'name' => 'Kram Perut',
                'category' => 'Fisik',
                'description' => 'Nyeri atau kram pada area perut bagian bawah.',
                'recommendation' => "Konsumsi makanan yang tinggi kalium seperti ubi jalar, pisang, salmon, kismis, kacang, dan yoghurt. Proses makanan yang dikukus atau dipanggang juga bisa menambah asupan kalium dalam tubuh."
            ],
            // ... tambahkan gejala lain jika ada
        ];

        foreach ($symptoms as $symptom) {
            Symptom::updateOrCreate(['name' => $symptom['name']], $symptom);
        }
    }
}
