<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

class WilayahLampungSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Bandar Lampung' => [
                'Bumi Waras', 'Enggal', 'Kedamaian', 'Kedaton', 'Kemiling',
                'Labuhan Ratu', 'Langkapura', 'Panjang', 'Rajabasa', 'Sukabumi',
                'Sukarame', 'Tanjung Karang Barat', 'Tanjung Karang Pusat',
                'Tanjung Karang Timur', 'Tanjung Senang', 'Teluk Betung Barat',
                'Teluk Betung Selatan', 'Teluk Betung Timur', 'Teluk Betung Utara',
                'Way Halim',
            ],

            'Metro' => [
                'Metro Barat', 'Metro Pusat', 'Metro Selatan',
                'Metro Timur', 'Metro Utara',
            ],

            'Lampung Selatan' => [
                'Bakauheni', 'Candipuro', 'Jati Agung', 'Kalianda', 'Katibung',
                'Ketapang', 'Merbau Mataram', 'Natar', 'Palas', 'Penengahan',
                'Rajabasa', 'Sidomulyo', 'Sragi', 'Tanjung Bintang',
                'Tanjung Sari', 'Way Panji', 'Way Sulan',
            ],

            'Lampung Tengah' => [
                'Anak Ratu Aji', 'Anak Tuha', 'Bandar Mataram', 'Bandar Surabaya',
                'Bangunrejo', 'Bekri', 'Bumi Nabung', 'Bumi Ratu Nuban',
                'Gunung Sugih', 'Kalirejo', 'Kota Gajah', 'Padang Ratu',
                'Pubian', 'Punggur', 'Putra Rumbia', 'Rumbia', 'Selagai Lingga',
                'Sendang Agung', 'Seputih Agung', 'Seputih Banyak',
                'Seputih Mataram', 'Seputih Raman', 'Seputih Surabaya',
                'Terbanggi Besar', 'Trimurjo', 'Way Pengubuan', 'Way Seputih',
            ],

            'Lampung Timur' => [
                'Bandar Sribhawono', 'Batanghari', 'Batanghari Nuban',
                'Braja Selebah', 'Bumi Agung', 'Gunung Pelindung',
                'Jabung', 'Labuhan Maringgai', 'Labuhan Ratu', 'Marga Sekampung',
                'Marga Tiga', 'Mataram Baru', 'Melinting', 'Metro Kibang',
                'Pasir Sakti', 'Pekalongan', 'Purbolinggo', 'Raman Utara',
                'Sekampung', 'Sekampung Udik', 'Sukadana', 'Waway Karya',
                'Way Bungur', 'Way Jepara',
            ],

            'Lampung Utara' => [
                'Abung Barat', 'Abung Kunang', 'Abung Pekurun', 'Abung Selatan',
                'Abung Semuli', 'Abung Surakarta', 'Abung Tengah', 'Abung Timur',
                'Abung Tinggi', 'Blambangan Pagar', 'Bukit Kemuning',
                'Bunga Mayang', 'Hulu Sungkai', 'Kotabumi', 'Kotabumi Selatan',
                'Kotabumi Utara', 'Muara Sungkai', 'Sungkai Barat',
                'Sungkai Jaya', 'Sungkai Selatan', 'Sungkai Tengah',
                'Sungkai Utara', 'Tanjung Raja',
            ],

            'Lampung Barat' => [
                'Air Hitam', 'Balik Bukit', 'Bandar Negeri Suoh', 'Batu Brak',
                'Batu Ketulis', 'Belalau', 'Gedung Surian', 'Kebun Tebu',
                'Lumbok Seminung', 'Pagar Dewa', 'Sekincau', 'Sukau',
                'Sumber Jaya', 'Suoh', 'Way Tenong',
            ],

            'Tanggamus' => [
                'Air Naningan', 'Bandar Negeri Semuong', 'Bulok', 'Cukuh Balak',
                'Gisting', 'Gunung Alip', 'Kelumbayan', 'Kelumbayan Barat',
                'Kota Agung', 'Kota Agung Barat', 'Kota Agung Timur',
                'Limau', 'Pematang Sawa', 'Pugung', 'Pulau Panggung',
                'Semaka', 'Sumberejo', 'Talang Padang', 'Ulubelu',
                'Wonosobo',
            ],

            'Tulang Bawang' => [
                'Banjar Agung', 'Banjar Baru', 'Banjar Margo', 'Dente Teladas',
                'Gedung Aji', 'Gedung Aji Baru', 'Gedung Meneng', 'Menggala',
                'Menggala Timur', 'Meraksa Aji', 'Penawar Aji',
                'Penawar Tama', 'Rawa Pitu', 'Rawajitu Selatan', 'Rawajitu Timur',
            ],

            'Tulang Bawang Barat' => [
                'Batu Putih', 'Gunung Agung', 'Gunung Terang', 'Lambu Kibang',
                'Pagar Dewa', 'Tulang Bawang Tengah', 'Tulang Bawang Udik',
                'Tumijajar', 'Way Kenanga',
            ],

            'Pesawaran' => [
                'Gedong Tataan', 'Kedondong', 'Marga Punduh', 'Negeri Katon',
                'Padang Cermin', 'Punduh Pidada', 'Tegineneng',
                'Teluk Pandan', 'Way Khilau', 'Way Lima', 'Way Ratai',
            ],

            'Pringsewu' => [
                'Adiluwih', 'Ambarawa', 'Banyumas', 'Gading Rejo',
                'Pagelaran', 'Pagelaran Utara', 'Pardasuka',
                'Pringsewu', 'Sukoharjo',
            ],

            'Mesuji' => [
                'Mesuji', 'Mesuji Timur', 'Panca Jaya', 'Rawa Jitu Utara',
                'Simpang Pematang', 'Tanjung Raya', 'Way Serdang',
            ],

            'Way Kanan' => [
                'Bahuga', 'Banjit', 'Baradatu', 'Blambangan Umpu',
                'Buay Bahuga', 'Bumi Agung', 'Gunung Labuhan',
                'Kasui', 'Negara Batin', 'Negeri Agung', 'Negeri Besar',
                'Pakuan Ratu', 'Rebang Tangkas', 'Way Tuba',
            ],

            'Pesisir Barat' => [
                'Bengkunat', 'Bengkunat Belimbing', 'Karya Penggawa',
                'Krui Selatan', 'Lemong', 'Ngambur', 'Ngaras',
                'Pesisir Selatan', 'Pesisir Tengah', 'Pesisir Utara',
                'Pulau Pisang', 'Way Krui',
            ],
        ];

        foreach ($data as $namaKabupaten => $kecamatans) {
            $kabupaten = Kabupaten::firstOrCreate([
                'nama' => $namaKabupaten,
            ]);

            foreach ($kecamatans as $namaKecamatan) {
                Kecamatan::firstOrCreate([
                    'kabupaten_id' => $kabupaten->id,
                    'nama' => $namaKecamatan,
                ]);
            }
        }
    }
}