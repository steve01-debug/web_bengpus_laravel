<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('struktur_organisasi_image_db')->insert([
            'id' => 1,
            'gambar' => 'assets/images/hero-bg.png'
        ]);

        $pimpinanData = [
            ['nama' => 'Brigadir Jenderal TNI Taufik Supriyadi', 'masa_jabatan' => '2024 - Sekarang', 'gambar' => 'assets/images/kabeng-taufik.jpg', 'is_current' => 1, 'urutan' => 95],
            ['nama' => 'Kolonel Chb Subiakto, S.E.', 'masa_jabatan' => '2023 - 2024', 'gambar' => 'assets/images/kabeng-subiakto.jpg', 'is_current' => 0, 'urutan' => 90],
            ['nama' => 'Kolonel Chb Nurcahyo Utomo, M.Si.', 'masa_jabatan' => '2021 - 2023', 'gambar' => 'assets/images/kabeng-nurcahyo.jpg', 'is_current' => 0, 'urutan' => 80],
            ['nama' => 'Kolonel Chb Muhammad Muhson', 'masa_jabatan' => '2020 - 2021', 'gambar' => 'assets/images/kabeng-muhson.jpg', 'is_current' => 0, 'urutan' => 70],
            ['nama' => 'Kolonel Chb Widodo, S.I.P.', 'masa_jabatan' => '2019 - 2020', 'gambar' => 'assets/images/kabeng-widodo.jpg', 'is_current' => 0, 'urutan' => 60],
            ['nama' => 'Kolonel Chb Fitri Taufiq Sahary, S.E., M.M.', 'masa_jabatan' => '2018 - 2019', 'gambar' => 'assets/images/kabeng-fitri.jpg', 'is_current' => 0, 'urutan' => 55],
            ['nama' => 'Kolonel Chb Jajat Drajat P., S.H.', 'masa_jabatan' => '2018 - 2018', 'gambar' => 'assets/images/kabeng-jajat.jpg', 'is_current' => 0, 'urutan' => 50],
            ['nama' => 'Kolonel Chb Leo Yulius Hillman', 'masa_jabatan' => '2016 - 2018', 'gambar' => 'assets/images/kabeng-leo.jpg', 'is_current' => 0, 'urutan' => 45],
            ['nama' => 'Kolonel Chb Zakaria', 'masa_jabatan' => '2015 - 2016', 'gambar' => 'assets/images/kabeng-zakaria.jpg', 'is_current' => 0, 'urutan' => 40],
            ['nama' => 'Kolonel Chb Totok', 'masa_jabatan' => '2014 - 2015', 'gambar' => 'assets/images/kabeng-totok.jpg', 'is_current' => 0, 'urutan' => 35],
            ['nama' => 'Kolonel Chb Sasmito Yupitoro, S.T.', 'masa_jabatan' => '2011 - 2014', 'gambar' => 'assets/images/kabeng-sasmito.jpg', 'is_current' => 0, 'urutan' => 30],
            ['nama' => 'Kolonel Chb Harijono, S.T.', 'masa_jabatan' => '2006 - 2011', 'gambar' => 'assets/images/kabeng-harijono.jpg', 'is_current' => 0, 'urutan' => 25],
            ['nama' => 'Kolonel Chb Sumarno', 'masa_jabatan' => '2003 - 2006', 'gambar' => 'assets/images/kabeng-sumarno.jpg', 'is_current' => 0, 'urutan' => 20],
            ['nama' => 'Kolonel Chb E. Supribadio. TE', 'masa_jabatan' => '1997 - 2003', 'gambar' => 'assets/images/kabeng-supribadio.jpg', 'is_current' => 0, 'urutan' => 18],
            ['nama' => 'Kolonel Chb Wiyono', 'masa_jabatan' => '1991 - 1997', 'gambar' => 'assets/images/kabeng-wiyono.jpg', 'is_current' => 0, 'urutan' => 16],
            ['nama' => 'Kolonel Chb Widoyo', 'masa_jabatan' => '1987 - 1991', 'gambar' => 'assets/images/kabeng-widoyo.jpg', 'is_current' => 0, 'urutan' => 14],
            ['nama' => 'Kolonel Chb Priyambodo', 'masa_jabatan' => '1980 - 1987', 'gambar' => 'assets/images/kabeng-priyambodo.jpg', 'is_current' => 0, 'urutan' => 12],
        ];

        foreach ($pimpinanData as $pimpinan) {
            DB::table('pimpinan_db')->insert($pimpinan);
        }

        $orgasData = [
            ['unsur' => 'pimpinan', 'jabatan' => 'KEPALA', 'nama' => 'Nama Kepala', 'urutan' => 10],
            ['unsur' => 'pimpinan', 'jabatan' => 'WAKIL KEPALA', 'nama' => 'Nama Wakil Kepala', 'urutan' => 20],
            ['unsur' => 'pembantu_pimpinan', 'jabatan' => 'KABAGUM', 'nama' => 'Nama Kabagum', 'urutan' => 30],
            ['unsur' => 'pembantu_pimpinan', 'jabatan' => 'KABAGRENDAL', 'nama' => 'Nama Kabagrendal', 'urutan' => 40],
            ['unsur' => 'pelayanan', 'jabatan' => 'PASITUUD', 'nama' => 'Nama Pasituud', 'urutan' => 50],
            ['unsur' => 'pelaksana_kabeng', 'jabatan' => 'KABENG SISKOM', 'nama' => 'Nama Kabeng Siskom', 'urutan' => 60],
            ['unsur' => 'pelaksana_subbeng_siskom', 'jabatan' => 'SUBBENG RADIO DIGILOG', 'nama' => 'Nama Kasubbeng', 'urutan' => 61],
            ['unsur' => 'pelaksana_subbeng_siskom', 'jabatan' => 'SUBBENG ALKOMSAL & MULTIMEDIA', 'nama' => 'Nama Kasubbeng', 'urutan' => 62],
            ['unsur' => 'pelaksana_subbeng_siskom', 'jabatan' => 'SUBBENG ALKOMSAT', 'nama' => 'Nama Kasubbeng', 'urutan' => 63],
            ['unsur' => 'pelaksana_kabeng', 'jabatan' => 'KABENG SISLEK', 'nama' => 'Nama Kabeng Sislek', 'urutan' => 70],
            ['unsur' => 'pelaksana_subbeng_sislek', 'jabatan' => 'SUBBENG ALDALLEK', 'nama' => 'Nama Kasubbeng', 'urutan' => 71],
            ['unsur' => 'pelaksana_subbeng_sislek', 'jabatan' => 'SUBBENG ALPERNIKA', 'nama' => 'Nama Kasubbeng', 'urutan' => 72],
            ['unsur' => 'pelaksana_subbeng_sislek', 'jabatan' => 'SUBBENG MATINDRALEK', 'nama' => 'Nama Kasubbeng', 'urutan' => 73],
            ['unsur' => 'pelaksana_subbeng_sislek', 'jabatan' => 'SUBBENG MEKATRONIKA', 'nama' => 'Nama Kasubbeng', 'urutan' => 74],
            ['unsur' => 'pelaksana_kabeng', 'jabatan' => 'KABENG JARINGAN DAN TIK', 'nama' => 'Nama Kabeng Jarnet TIK', 'urutan' => 80],
            ['unsur' => 'pelaksana_subbeng_jarnet', 'jabatan' => 'SUBBENG JARKABEL', 'nama' => 'Nama Kasubbeng', 'urutan' => 81],
            ['unsur' => 'pelaksana_subbeng_jarnet', 'jabatan' => 'SUBBENG JARNIRKABEL', 'nama' => 'Nama Kasubbeng', 'urutan' => 82],
            ['unsur' => 'pelaksana_subbeng_jarnet', 'jabatan' => 'SUBBENG TIK', 'nama' => 'Nama Kasubbeng', 'urutan' => 83],
            ['unsur' => 'pelaksana_kabeng', 'jabatan' => 'KABENG INTEGRASI & POWER SYSTEM', 'nama' => 'Nama Kabeng Integrasi', 'urutan' => 90],
            ['unsur' => 'pelaksana_subbeng_integrasi', 'jabatan' => 'SUBBENG INTEGRASI', 'nama' => 'Nama Kasubbeng', 'urutan' => 91],
            ['unsur' => 'pelaksana_subbeng_integrasi', 'jabatan' => 'SUBBENG POWER SYSTEM', 'nama' => 'Nama Kasubbeng', 'urutan' => 92],
            ['unsur' => 'pelaksana_kabeng', 'jabatan' => 'KAGUD', 'nama' => 'Nama Kagud', 'urutan' => 100],
        ];

        foreach ($orgasData as $orgas) {
            DB::table('struktur_organisasi_db')->insert($orgas);
        }
    }
}
