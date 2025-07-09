<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $classifications = DB::table('classifications')
            ->whereIn('name', ['Perdesaan', 'Perkotaan'])
            ->pluck('id', 'name');
        $districts = DB::table('districts')
            ->whereIn('code', [
                '3302010', // Lumbir
                '3302020', // Wangon
                '3302030', // Jatilawang
                '3302040', // Rawalo
                '3302050', // Kebasen
                '3302060', // Kemranjen
                '3302070', // Sumpiuh
                '3302080', // Tambak
                '3302090', // Somagede
                '3302100', // Kalibagor
                '3302110', // Banyumas
                '3302120', // Patikraja
                '3302130', // Purwojati
                '3302140', // Ajibarang
                '3302150', // Gumelar
                '3302160', // Pekuncen
                '3302170', // Cilongok
                '3302180', // Karanglewas
                '3302190', // Kedung Banteng
                '3302200', // Baturraden
                '3302210', // Sumbang
                '3302220', // Kembaran
                '3302230', // Sokaraja
                '3302710', // Purwokerto Selatan
                '3302720', // Purwokerto Barat
                '3302730', // Purwokerto Timur
                '3302740', // Purwokerto Utara
            ])->pluck('id', 'code');
        $perdesaanId = $classifications['Perdesaan'] ?? 2;
        $perkotaanId = $classifications['Perkotaan'] ?? 1;

        // District 3302010: Lumbir
        $lumbir = [
            ['code' => '001', 'name' => 'CINGEBUL'],
            ['code' => '002', 'name' => 'KEDUNGGEDE'],
            ['code' => '003', 'name' => 'CIDORA'],
            ['code' => '004', 'name' => 'BESUKI'],
            ['code' => '005', 'name' => 'PARUNGKAMAL'],
            ['code' => '006', 'name' => 'CIRAHAB'],
            ['code' => '007', 'name' => 'CANDUK'],
            ['code' => '008', 'name' => 'KARANGGAYAM'],
            ['code' => '009', 'name' => 'LUMBIR'],
            ['code' => '010', 'name' => 'DERMAJI'],
        ];
        foreach ($lumbir as &$v) {
            $v['code'] = '3302010' . $v['code'];
            $v['district_id'] = $districts['3302010'];
            // Default: Perdesaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perdesaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($lumbir);

        // District 3302020: Wangon
        $wangon = [
            ['code' => '001', 'name' => 'RANDEGAN'],
            ['code' => '002', 'name' => 'RAWAHENG'],
            ['code' => '003', 'name' => 'PANGADEGAN', 'classification_id' => $perdesaanId],
            ['code' => '004', 'name' => 'KLAPAGADING'],
            ['code' => '005', 'name' => 'KLAPAGADING KULON'],
            ['code' => '006', 'name' => 'WANGON'],
            ['code' => '007', 'name' => 'BANTERAN'],
            ['code' => '008', 'name' => 'JAMBU'],
            ['code' => '009', 'name' => 'JURANGBAHAS', 'classification_id' => $perdesaanId],
            ['code' => '010', 'name' => 'CIKAKAK', 'classification_id' => $perdesaanId],
            ['code' => '011', 'name' => 'WLAHAR'],
            ['code' => '012', 'name' => 'WINDUNEGARA'],
        ];
        foreach ($wangon as &$v) {
            $v['code'] = '3302020' . $v['code'];
            $v['district_id'] = $districts['3302020'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($wangon);

        // District 3302030: Jatilawang
        $jatilawang = [
            ['code' => '001', 'name' => 'GUNUNG WETAN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'PEKUNCEN', 'classification_id' => $perdesaanId],
            ['code' => '003', 'name' => 'KARANGLEWAS', 'classification_id' => $perdesaanId],
            ['code' => '004', 'name' => 'KARANGANYAR', 'classification_id' => $perdesaanId],
            ['code' => '005', 'name' => 'MARGASANA'],
            ['code' => '006', 'name' => 'ADISARA'],
            ['code' => '007', 'name' => 'KEDUNGWRINGIN'],
            ['code' => '008', 'name' => 'BANTAR', 'classification_id' => $perdesaanId],
            ['code' => '009', 'name' => 'TINGGARJAYA'],
            ['code' => '010', 'name' => 'TUNJUNG'],
            ['code' => '011', 'name' => 'GENTAWANGI'],
        ];
        foreach ($jatilawang as &$v) {
            $v['code'] = '3302030' . $v['code'];
            $v['district_id'] = $districts['3302030'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($jatilawang);

        // District 3302040: Rawalo
        $rawalo = [
            ['code' => '001', 'name' => 'LOSARI', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'MENGANTI'],
            ['code' => '003', 'name' => 'BANJARPARAKAN'],
            ['code' => '004', 'name' => 'RAWALO'],
            ['code' => '005', 'name' => 'TAMBAKNEGARA'],
            ['code' => '006', 'name' => 'SIDAMULIH'],
            ['code' => '007', 'name' => 'PESAWAHAN'],
            ['code' => '008', 'name' => 'TIPAR'],
            ['code' => '009', 'name' => 'SANGGREMAN', 'classification_id' => $perdesaanId],
        ];
        foreach ($rawalo as &$v) {
            $v['code'] = '3302040' . $v['code'];
            $v['district_id'] = $districts['3302040'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($rawalo);

        // District 3302050: Kebasen
        $kebasen = [
            ['code' => '001', 'name' => 'ADISANA'],
            ['code' => '002', 'name' => 'BANGSA'],
            ['code' => '003', 'name' => 'KARANGSARI'],
            ['code' => '004', 'name' => 'RANDEGAN'],
            ['code' => '005', 'name' => 'KALIWEDI'],
            ['code' => '006', 'name' => 'SAWANGAN'],
            ['code' => '007', 'name' => 'KALISALAK'],
            ['code' => '008', 'name' => 'CINDAGA'],
            ['code' => '009', 'name' => 'KEBASEN'],
            ['code' => '010', 'name' => 'GAMBARSARI'],
            ['code' => '011', 'name' => 'TUMIYANG'],
            ['code' => '012', 'name' => 'MANDIRANCAN'],
        ];
        foreach ($kebasen as &$v) {
            $v['code'] = '3302050' . $v['code'];
            $v['district_id'] = $districts['3302050'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($kebasen);

        // District 3302060: Kemranjen
        $kemranjen = [
            ['code' => '001', 'name' => 'GRUJUGAN'],
            ['code' => '002', 'name' => 'SIRAU'],
            ['code' => '003', 'name' => 'SIBALUNG'],
            ['code' => '004', 'name' => 'SIBRAMA'],
            ['code' => '005', 'name' => 'KEDUNGPRING'],
            ['code' => '006', 'name' => 'KECILA'],
            ['code' => '007', 'name' => 'NUSAMANGIR', 'classification_id' => $perdesaanId],
            ['code' => '008', 'name' => 'KARANGJATI'],
            ['code' => '009', 'name' => 'KEBARONGAN'],
            ['code' => '010', 'name' => 'SIDAMULYA'],
            ['code' => '011', 'name' => 'PAGERALANG'],
            ['code' => '012', 'name' => 'ALASMALANG'],
            ['code' => '013', 'name' => 'PETARANGAN'],
            ['code' => '014', 'name' => 'KARANGGINTUNG', 'classification_id' => $perdesaanId],
            ['code' => '015', 'name' => 'KARANGSALAM', 'classification_id' => $perdesaanId],
        ];
        foreach ($kemranjen as &$v) {
            $v['code'] = '3302060' . $v['code'];
            $v['district_id'] = $districts['3302060'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($kemranjen);

        // District 3302070: Sumpiuh
        $sumpiuh = [
            ['code' => '001', 'name' => 'PANDAK'],
            ['code' => '002', 'name' => 'KUNTILI'],
            ['code' => '003', 'name' => 'KEMIRI'],
            ['code' => '004', 'name' => 'KARANGGEDANG', 'classification_id' => $perdesaanId],
            ['code' => '005', 'name' => 'NUSADADI', 'classification_id' => $perdesaanId],
            ['code' => '006', 'name' => 'SELANDAKA'],
            ['code' => '007', 'name' => 'SUMPIUH'],
            ['code' => '008', 'name' => 'KRADENAN'],
            ['code' => '009', 'name' => 'SELANEGARA'],
            ['code' => '010', 'name' => 'KEBOKURA'],
            ['code' => '011', 'name' => 'LEBENG', 'classification_id' => $perdesaanId],
            ['code' => '012', 'name' => 'KETANDA'],
            ['code' => '013', 'name' => 'BANJARPANEPEN', 'classification_id' => $perdesaanId],
            ['code' => '014', 'name' => 'BOGANGIN'],
        ];
        foreach ($sumpiuh as &$v) {
            $v['code'] = '3302070' . $v['code'];
            $v['district_id'] = $districts['3302070'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($sumpiuh);

        // District 3302080: Tambak
        $tambak = [
            ['code' => '001', 'name' => 'PLANGKAPAN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'GUMELAR LOR'],
            ['code' => '003', 'name' => 'GUMELAR KIDUL'],
            ['code' => '004', 'name' => 'KARANGPETIR'],
            ['code' => '005', 'name' => 'GEBANGSARI', 'classification_id' => $perdesaanId],
            ['code' => '006', 'name' => 'KARANGPUCUNG'],
            ['code' => '007', 'name' => 'PREMBUN'],
            ['code' => '008', 'name' => 'PESANTREN'],
            ['code' => '009', 'name' => 'BUNIAYU'],
            ['code' => '010', 'name' => 'PURWODADI'],
            ['code' => '011', 'name' => 'KAMULYAN'],
            ['code' => '012', 'name' => 'WATUAGUNG'],
        ];
        foreach ($tambak as &$v) {
            $v['code'] = '3302080' . $v['code'];
            $v['district_id'] = $districts['3302080'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($tambak);

        // District 3302090: Somagede
        $somagede = [
            ['code' => '001', 'name' => 'TANGGERAN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'SOKAWERA'],
            ['code' => '003', 'name' => 'SOMAGEDE'],
            ['code' => '004', 'name' => 'KLINTING'],
            ['code' => '005', 'name' => 'KEMAWI', 'classification_id' => $perdesaanId],
            ['code' => '006', 'name' => 'PIASA KULON'],
            ['code' => '007', 'name' => 'KANDING'],
            ['code' => '008', 'name' => 'SOMAKATON', 'classification_id' => $perdesaanId],
            ['code' => '009', 'name' => 'PLANA', 'classification_id' => $perdesaanId],
        ];
        foreach ($somagede as &$v) {
            $v['code'] = '3302090' . $v['code'];
            $v['district_id'] = $districts['3302090'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($somagede);

        // District 3302100: Kalibagor
        $kalibagor = [
            ['code' => '001', 'name' => 'SROWOT'],
            ['code' => '002', 'name' => 'SURO', 'classification_id' => $perdesaanId],
            ['code' => '003', 'name' => 'KALIORI'],
            ['code' => '004', 'name' => 'WLAHAR WETAN'],
            ['code' => '005', 'name' => 'PEKAJA'],
            ['code' => '006', 'name' => 'KARANGDADAP'],
            ['code' => '007', 'name' => 'KALIBAGOR'],
            ['code' => '008', 'name' => 'PAJERUKAN'],
            ['code' => '009', 'name' => 'PETIR'],
            ['code' => '010', 'name' => 'KALICUPAK KIDUL'],
            ['code' => '011', 'name' => 'KALICUPAK LOR'],
            ['code' => '012', 'name' => 'KALISOGRA WETAN'],
        ];
        foreach ($kalibagor as &$v) {
            $v['code'] = '3302100' . $v['code'];
            $v['district_id'] = $districts['3302100'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($kalibagor);

        // District 3302110: Banyumas
        $banyumas = [
            ['code' => '001', 'name' => 'BINANGUN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'PASINGGANGAN'],
            ['code' => '003', 'name' => 'KEDUNGGEDE'],
            ['code' => '004', 'name' => 'KARANGRAU'],
            ['code' => '005', 'name' => 'KEJAWAR'],
            ['code' => '006', 'name' => 'DANARAJA'],
            ['code' => '007', 'name' => 'KEDUNGUTER'],
            ['code' => '008', 'name' => 'SUDAGARAN'],
            ['code' => '009', 'name' => 'PEKUNDEN'],
            ['code' => '010', 'name' => 'KALISUBE'],
            ['code' => '011', 'name' => 'DAWUHAN'],
            ['code' => '012', 'name' => 'PAPRINGAN'],
        ];
        foreach ($banyumas as &$v) {
            $v['code'] = '3302110' . $v['code'];
            $v['district_id'] = $districts['3302110'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($banyumas);

        // District 3302120: Patikraja
        $patikraja = [
            ['code' => '001', 'name' => 'SAWANGAN WETAN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'KARANGENDEP', 'classification_id' => $perdesaanId],
            ['code' => '003', 'name' => 'NOTOG'],
            ['code' => '004', 'name' => 'PATIKRAJA'],
            ['code' => '005', 'name' => 'PEGALONGAN'],
            ['code' => '006', 'name' => 'SOKAWERA'],
            ['code' => '007', 'name' => 'WLAHAR KULON'],
            ['code' => '008', 'name' => 'KEDUNGRANDU'],
            ['code' => '009', 'name' => 'KEDUNGWULUH KIDUL'],
            ['code' => '010', 'name' => 'KEDUNGWULUH LOR'],
            ['code' => '011', 'name' => 'KARANGANYAR'],
            ['code' => '012', 'name' => 'SIDABOWA'],
            ['code' => '013', 'name' => 'KEDUNGWRINGIN'],
        ];
        foreach ($patikraja as &$v) {
            $v['code'] = '3302120' . $v['code'];
            $v['district_id'] = $districts['3302120'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($patikraja);

        // District 3302130: Purwojati
        $purwojati = [
            ['code' => '001', 'name' => 'GERDUREN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'KARANGTALUN KIDUL'],
            ['code' => '003', 'name' => 'KALIURIP', 'classification_id' => $perdesaanId],
            ['code' => '004', 'name' => 'KARANGTALUN LOR'],
            ['code' => '005', 'name' => 'PURWOJATI'],
            ['code' => '006', 'name' => 'KLAPASAWIT'],
            ['code' => '007', 'name' => 'KARANGMANGU', 'classification_id' => $perdesaanId],
            ['code' => '008', 'name' => 'KALIPUTIH', 'classification_id' => $perdesaanId],
            ['code' => '009', 'name' => 'KALIWANGI', 'classification_id' => $perdesaanId],
            ['code' => '010', 'name' => 'KALITAPEN'],
        ];
        foreach ($purwojati as &$v) {
            $v['code'] = '3302130' . $v['code'];
            $v['district_id'] = $districts['3302130'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($purwojati);

        // District 3302140: Ajibarang
        $ajibarang = [
            ['code' => '001', 'name' => 'DARMAKRADENAN', 'classification_id' => $perdesaanId],
            ['code' => '002', 'name' => 'TIPARKIDUL'],
            ['code' => '003', 'name' => 'SAWANGAN'],
            ['code' => '004', 'name' => 'JINGKANG', 'classification_id' => $perdesaanId],
            ['code' => '005', 'name' => 'BANJARSARI'],
            ['code' => '006', 'name' => 'KALIBENDA'],
            ['code' => '007', 'name' => 'PANCURENDANG'],
            ['code' => '008', 'name' => 'PANCASAN'],
            ['code' => '009', 'name' => 'KARANGBAWANG'],
            ['code' => '010', 'name' => 'KRACAK'],
            ['code' => '011', 'name' => 'AJIBARANG KULON'],
            ['code' => '012', 'name' => 'AJIBARANG WETAN'],
            ['code' => '013', 'name' => 'LESMANA'],
            ['code' => '014', 'name' => 'PANDANSARI'],
            ['code' => '015', 'name' => 'CIBERUNG'],
        ];
        foreach ($ajibarang as &$v) {
            $v['code'] = '3302140' . $v['code'];
            $v['district_id'] = $districts['3302140'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($ajibarang);

        // District 3302150: Gumelar
        $gumelar = [
            ['code' => '001', 'name' => 'CILANGKAP'],
            ['code' => '002', 'name' => 'CIHONJE'],
            ['code' => '003', 'name' => 'PANINGKABAN'],
            ['code' => '004', 'name' => 'KARANGKEMOJING'],
            ['code' => '005', 'name' => 'GANCANG', 'classification_id' => $perkotaanId],
            ['code' => '006', 'name' => 'KEDUNGURANG', 'classification_id' => $perkotaanId],
            ['code' => '007', 'name' => 'GUMELAR', 'classification_id' => $perkotaanId],
            ['code' => '008', 'name' => 'TLAGA'],
            ['code' => '009', 'name' => 'SAMUDRA'],
            ['code' => '010', 'name' => 'SAMUDRA KULON'],
        ];
        foreach ($gumelar as &$v) {
            $v['code'] = '3302150' . $v['code'];
            $v['district_id'] = $districts['3302150'];
            // Default: Perdesaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perdesaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($gumelar);

        // District 3302160: Pekuncen
        $pekuncen = [
            ['code' => '001', 'name' => 'CIBANGKONG'],
            ['code' => '002', 'name' => 'PETAHUNAN', 'classification_id' => $perdesaanId],
            ['code' => '003', 'name' => 'SEMEDO', 'classification_id' => $perdesaanId],
            ['code' => '004', 'name' => 'CIKAWUNG'],
            ['code' => '005', 'name' => 'KARANGKLESEM'],
            ['code' => '006', 'name' => 'CANDINEGARA'],
            ['code' => '007', 'name' => 'CIKEMBULAN'],
            ['code' => '008', 'name' => 'TUMIYANG'],
            ['code' => '009', 'name' => 'GLEMPANG', 'classification_id' => $perdesaanId],
            ['code' => '010', 'name' => 'PEKUNCEN'],
            ['code' => '011', 'name' => 'PASIRAMAN LOR'],
            ['code' => '012', 'name' => 'PASIRAMAN KIDUL'],
            ['code' => '013', 'name' => 'BANJARANYAR'],
            ['code' => '014', 'name' => 'KARANGKEMIRI'],
            ['code' => '015', 'name' => 'KRANGGAN'],
            ['code' => '016', 'name' => 'KRAJAN'],
        ];
        foreach ($pekuncen as &$v) {
            $v['code'] = '3302160' . $v['code'];
            $v['district_id'] = $districts['3302160'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($pekuncen);

        // District 3302170: Cilongok
        $cilongok = [
            ['code' => '001', 'name' => 'BATUANTEN'],
            ['code' => '002', 'name' => 'KASEGERAN'],
            ['code' => '003', 'name' => 'JATISABA', 'classification_id' => $perdesaanId],
            ['code' => '004', 'name' => 'PANUSUPAN'],
            ['code' => '005', 'name' => 'PEJOGOL'],
            ['code' => '006', 'name' => 'PAGERAJI'],
            ['code' => '007', 'name' => 'SUDIMARA'],
            ['code' => '008', 'name' => 'CILONGOK'],
            ['code' => '009', 'name' => 'CIPETE'],
            ['code' => '010', 'name' => 'CIKIDANG'],
            ['code' => '011', 'name' => 'PERNASIDI'],
            ['code' => '012', 'name' => 'LANGGONGSARI'],
            ['code' => '013', 'name' => 'RANCAMAYA'],
            ['code' => '014', 'name' => 'PANEMBANGAN'],
            ['code' => '015', 'name' => 'KARANGLO'],
            ['code' => '016', 'name' => 'KALISARI'],
            ['code' => '017', 'name' => 'KARANGTENGAH', 'classification_id' => $perdesaanId],
            ['code' => '018', 'name' => 'SAMBIRATA', 'classification_id' => $perdesaanId],
            ['code' => '019', 'name' => 'GUNUNGLURAH'],
            ['code' => '020', 'name' => 'SOKAWERA'],
        ];
        foreach ($cilongok as &$v) {
            $v['code'] = '3302170' . $v['code'];
            $v['district_id'] = $districts['3302170'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($cilongok);

        // District 3302180: Karanglewas
        $karanglewas = [
            ['code' => '001', 'name' => 'KEDIRI'],
            ['code' => '002', 'name' => 'PANGEBATAN'],
            ['code' => '003', 'name' => 'KARANGLEWAS KIDUL'],
            ['code' => '004', 'name' => 'TAMANSARI'],
            ['code' => '005', 'name' => 'KARANGKEMIRI'],
            ['code' => '006', 'name' => 'KARANGGUDE KULON'],
            ['code' => '007', 'name' => 'PASIR KULON'],
            ['code' => '008', 'name' => 'PASIR WETAN'],
            ['code' => '009', 'name' => 'PASIR LOR'],
            ['code' => '010', 'name' => 'JIPANG'],
            ['code' => '011', 'name' => 'SINGASARI'],
            ['code' => '012', 'name' => 'BABAKAN'],
            ['code' => '013', 'name' => 'SUNYALANGU'],
        ];
        foreach ($karanglewas as &$v) {
            $v['code'] = '3302180' . $v['code'];
            $v['district_id'] = $districts['3302180'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($karanglewas);

        // District 3302190: Kedung Banteng
        $kedungBanteng = [
            ['code' => '001', 'name' => 'KEDUNGBANTENG'],
            ['code' => '002', 'name' => 'KEBOCORAN'],
            ['code' => '003', 'name' => 'KARANGSALAM KIDUL'],
            ['code' => '004', 'name' => 'BEJI'],
            ['code' => '005', 'name' => 'KARANGNANGKA'],
            ['code' => '006', 'name' => 'KENITEN'],
            ['code' => '007', 'name' => 'DAWUHAN WETAN'],
            ['code' => '008', 'name' => 'DAWUHAN KULON'],
            ['code' => '009', 'name' => 'BASEH', 'classification_id' => $perdesaanId],
            ['code' => '010', 'name' => 'KALISALAK', 'classification_id' => $perdesaanId],
            ['code' => '011', 'name' => 'WINDUJAYA', 'classification_id' => $perdesaanId],
            ['code' => '012', 'name' => 'KALIKESUR', 'classification_id' => $perdesaanId],
            ['code' => '013', 'name' => 'KUTALIMAN'],
            ['code' => '014', 'name' => 'MELUNG', 'classification_id' => $perdesaanId],
        ];
        foreach ($kedungBanteng as &$v) {
            $v['code'] = '3302190' . $v['code'];
            $v['district_id'] = $districts['3302190'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($kedungBanteng);

        // // District 3302200: Baturraden
        $baturraden = [
            ['code' => '001', 'name' => 'PURWOSARI'],
            ['code' => '002', 'name' => 'KUTASARI'],
            ['code' => '003', 'name' => 'PANDAK'],
            ['code' => '004', 'name' => 'PAMIJEN'],
            ['code' => '005', 'name' => 'REMPOAH'],
            ['code' => '006', 'name' => 'KEBUMEN'],
            ['code' => '007', 'name' => 'KARANGTENGAH'],
            ['code' => '008', 'name' => 'KEMUTUG KIDUL'],
            ['code' => '009', 'name' => 'KARANGSALAM'],
            ['code' => '010', 'name' => 'KEMUTUG LOR'],
            ['code' => '011', 'name' => 'KARANGMANGU'],
            ['code' => '012', 'name' => 'KETENGER'],
        ];
        foreach ($baturraden as &$v) {
            $v['code'] = '3302200' . $v['code'];
            $v['district_id'] = $districts['3302200'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($baturraden);

        // District 3302210: Sumbang
        $sumbang = [
            ['code' => '001', 'name' => 'KARANGGINTUNG'],
            ['code' => '002', 'name' => 'TAMBAKSOGRA'],
            ['code' => '003', 'name' => 'KARANGCEGAK'],
            ['code' => '004', 'name' => 'KARANGTURI', 'classification_id' => $perdesaanId],
            ['code' => '005', 'name' => 'SILADO'],
            ['code' => '006', 'name' => 'SUSUKAN'],
            ['code' => '007', 'name' => 'SUMBANG'],
            ['code' => '008', 'name' => 'KEBANGGAN'],
            ['code' => '009', 'name' => 'KAWUNGCARANG'],
            ['code' => '010', 'name' => 'DATAR'],
            ['code' => '011', 'name' => 'BANJARSARI KULON'],
            ['code' => '012', 'name' => 'BANJARSARI WETAN'],
            ['code' => '013', 'name' => 'BANTERAN'],
            ['code' => '014', 'name' => 'CIBEREM'],
            ['code' => '015', 'name' => 'SIKAPAT'],
            ['code' => '016', 'name' => 'GANDATAPA'],
            ['code' => '017', 'name' => 'KOTAYASA'],
            ['code' => '018', 'name' => 'LIMPAKUWUS', 'classification_id' => $perdesaanId],
            ['code' => '019', 'name' => 'KEDUNG MALANG'],
        ];
        foreach ($sumbang as &$v) {
            $v['code'] = '3302210' . $v['code'];
            $v['district_id'] = $districts['3302210'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($sumbang);

        // District 3302220: Kembaran
        $kembaran = [
            ['code' => '001', 'name' => 'LEDUG'],
            ['code' => '002', 'name' => 'PLIKEN'],
            ['code' => '003', 'name' => 'PURWODADI'],
            ['code' => '004', 'name' => 'KARANGTENGAH'],
            ['code' => '005', 'name' => 'KRAMAT'],
            ['code' => '006', 'name' => 'SAMBENG WETAN'],
            ['code' => '007', 'name' => 'SAMBENG KULON'],
            ['code' => '008', 'name' => 'PURBADANA'],
            ['code' => '009', 'name' => 'KEMBARAN'],
            ['code' => '010', 'name' => 'BOJONGSARI'],
            ['code' => '011', 'name' => 'KARANGSOKA'],
            ['code' => '012', 'name' => 'DUKUHWALUH'],
            ['code' => '013', 'name' => 'TAMBAKSARI KIDUL'],
            ['code' => '014', 'name' => 'BANTARWUNI'],
            ['code' => '015', 'name' => 'KARANGSARI'],
            ['code' => '016', 'name' => 'LINGGASARI'],
        ];
        foreach ($kembaran as &$v) {
            $v['code'] = '3302220' . $v['code'];
            $v['district_id'] = $districts['3302220'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($kembaran);

        // District 3302230: Sokaraja
        $sokaraja = [
            ['code' => '001', 'name' => 'KALIKIDANG'],
            ['code' => '002', 'name' => 'SOKARAJA TENGAH'],
            ['code' => '003', 'name' => 'SOKARAJA KIDUL'],
            ['code' => '004', 'name' => 'SOKARAJA WETAN'],
            ['code' => '005', 'name' => 'KLAHANG'],
            ['code' => '006', 'name' => 'BANJARSARI KIDUL'],
            ['code' => '007', 'name' => 'JOMPO KULON'],
            ['code' => '008', 'name' => 'BANJARANYAR'],
            ['code' => '009', 'name' => 'LEMBERANG'],
            ['code' => '010', 'name' => 'KARANGDUREN'],
            ['code' => '011', 'name' => 'SOKARAJA LOR'],
            ['code' => '012', 'name' => 'KEDONDONG'],
            ['code' => '013', 'name' => 'PAMIJEN'],
            ['code' => '014', 'name' => 'SOKARAJA KULON'],
            ['code' => '015', 'name' => 'KARANGKEDAWUNG'],
            ['code' => '016', 'name' => 'WIRADADI'],
            ['code' => '017', 'name' => 'KARANGNANAS'],
            ['code' => '018', 'name' => 'KARANGRAU'],
        ];
        foreach ($sokaraja as &$v) {
            $v['code'] = '3302230' . $v['code'];
            $v['district_id'] = $districts['3302230'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($sokaraja);

        // District 3302710: Purwokerto Selatan
        $purwokertoSelatan = [
            ['code' => '001', 'name' => 'KARANGKLESEM'],
            ['code' => '002', 'name' => 'TELUK'],
            ['code' => '003', 'name' => 'BERKOH'],
            ['code' => '004', 'name' => 'PURWOKERTO KIDUL'],
            ['code' => '005', 'name' => 'PURWOKERTO KULON'],
            ['code' => '006', 'name' => 'KARANGPUCUNG'],
            ['code' => '007', 'name' => 'TANJUNG'],
        ];
        foreach ($purwokertoSelatan as &$v) {
            $v['code'] = '3302710' . $v['code'];
            $v['district_id'] = $districts['3302710'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($purwokertoSelatan);

        // District 3302720: Purwokerto Barat
        $purwokertoBarat = [
            ['code' => '001', 'name' => 'KARANGLEWAS LOR'],
            ['code' => '002', 'name' => 'PASIR KIDUL'],
            ['code' => '003', 'name' => 'REJASARI'],
            ['code' => '004', 'name' => 'PASIRMUNCANG'],
            ['code' => '005', 'name' => 'BANTARSOKA'],
            ['code' => '006', 'name' => 'KOBER'],
            ['code' => '007', 'name' => 'KEDUNGWULUH'],
        ];
        foreach ($purwokertoBarat as &$v) {
            $v['code'] = '3302720' . $v['code'];
            $v['district_id'] = $districts['3302720'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($purwokertoBarat);

        // District 3302730: Purwokerto Timur
        $purwokertoTimur = [
            ['code' => '001', 'name' => 'SOKANEGARA'],
            ['code' => '002', 'name' => 'KRANJI'],
            ['code' => '003', 'name' => 'PURWOKERTO LOR'],
            ['code' => '004', 'name' => 'PURWOKERTO WETAN'],
            ['code' => '005', 'name' => 'MERSI'],
            ['code' => '006', 'name' => 'ARCAWINANGUN'],
        ];
        foreach ($purwokertoTimur as &$v) {
            $v['code'] = '3302730' . $v['code'];
            $v['district_id'] = $districts['3302730'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($purwokertoTimur);

        // District 3302740: Purwokerto Utara
        $purwokertoUtara = [
            ['code' => '001', 'name' => 'BOBOSAN'],
            ['code' => '002', 'name' => 'PURWANEGARA'],
            ['code' => '003', 'name' => 'BANCARKEMBAR'],
            ['code' => '004', 'name' => 'SUMAMPIR'],
            ['code' => '005', 'name' => 'PABUARAN'],
            ['code' => '006', 'name' => 'GRENDENG'],
            ['code' => '007', 'name' => 'KARANGWANGKAL'],
        ];
        foreach ($purwokertoUtara as &$v) {
            $v['code'] = '3302740' . $v['code'];
            $v['district_id'] = $districts['3302740'];
            // Default: Perkotaan
            if (!isset($v['classification_id'])) {
                $v['classification_id'] = $perkotaanId;
            }
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        Village::insert($purwokertoUtara);
    }
}
