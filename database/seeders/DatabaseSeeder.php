<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\CutiRecord;
use App\Models\KontakLayanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'admin',
            'email' => 'admin@si.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // Dummy Mahasiswa
        $m1 = Mahasiswa::create([
            'nim' => '2021001001',
            'nama' => 'Budi Santoso',
            'angkatan' => '2021',
            'semester' => 7,
            'status' => 'aktif',
            'no_whatsapp' => '6281234567890',
            'email' => 'budi@mhs.si.ac.id',
            'ipk' => 3.85,
            'sks_tempuh' => 120,
        ]);

        $m2 = Mahasiswa::create([
            'nim' => '2021001002',
            'nama' => 'Siti Nurhaliza',
            'angkatan' => '2021',
            'semester' => 7,
            'status' => 'cuti',
            'no_whatsapp' => '6289876543210',
            'email' => 'siti@mhs.si.ac.id',
            'ipk' => 3.40,
            'sks_tempuh' => 100,
        ]);

        $m3 = Mahasiswa::create([
            'nim' => '2020002015',
            'nama' => 'Ahmad Fauzi',
            'angkatan' => '2020',
            'semester' => 9,
            'status' => 'aktif',
            'no_whatsapp' => '628111222333',
            'email' => 'ahmad@mhs.si.ac.id',
            'ipk' => 3.10,
            'sks_tempuh' => 140,
        ]);

        $m4 = Mahasiswa::create([
            'nim' => '2022003008',
            'nama' => 'Dewi Lestari',
            'angkatan' => '2022',
            'semester' => 5,
            'status' => 'drop_out',
            'no_whatsapp' => '628555666777',
            'email' => 'dewi@mhs.si.ac.id',
            'ipk' => 1.50,
            'sks_tempuh' => 45,
        ]);

        // Dummy Pengajuan
        Pengajuan::create([
            'mahasiswa_id' => $m1->id,
            'jenis_layanan' => 'Surat Keterangan Aktif',
            'keterangan' => 'Untuk keperluan beasiswa',
            'status' => 'menunggu',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Pengajuan::create([
            'mahasiswa_id' => $m2->id,
            'jenis_layanan' => 'Pengajuan Cuti Akademik',
            'keterangan' => 'Sakit berkepanjangan',
            'status' => 'diproses',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        Pengajuan::create([
            'mahasiswa_id' => $m3->id,
            'jenis_layanan' => 'Legalisasi Dokumen',
            'keterangan' => 'Legalisir Transkrip',
            'status' => 'selesai',
            'created_at' => Carbon::now()->subDays(10),
        ]);

        // Dummy CutiRecord (Untuk Pengingat Cuti)
        CutiRecord::create([
            'mahasiswa_id' => $m2->id,
            'tanggal_mulai' => Carbon::now()->subMonths(5),
            'tanggal_selesai' => Carbon::now()->addDays(7), // Akan muncul di reminder
            'alasan' => 'Sakit',
            'status_notif' => 'belum',
        ]);

        $m5 = Mahasiswa::create([
            'nim' => '2021001010',
            'nama' => 'Anisa Rahman',
            'angkatan' => '2021',
            'semester' => 7,
            'status' => 'cuti',
            'no_whatsapp' => '628777888999',
            'email' => 'anisa@mhs.si.ac.id',
            'ipk' => 3.60,
            'sks_tempuh' => 110,
        ]);

        CutiRecord::create([
            'mahasiswa_id' => $m5->id,
            'tanggal_mulai' => Carbon::now()->subMonths(6),
            'tanggal_selesai' => Carbon::now()->addDays(3), // Sangat urgent
            'alasan' => 'Pertukaran Pelajar',
            'status_notif' => 'belum',
        ]);

        // Dummy Kontak Layanan
        KontakLayanan::create([
            'nama_kontak' => 'Admin Prodi',
            'jabatan' => 'Staf Layanan Administrasi',
            'no_whatsapp' => '6281234567891',
            'pesan_template' => "Halo {nama_mahasiswa} (NIM: {nim}),\n\nKami ingin menginformasikan bahwa masa cuti akademik Anda akan berakhir dalam {sisa_hari} hari lagi.\n\nSilakan segera mengurus perpanjangan cuti atau aktivasi kembali ke bagian akademik Prodi Sistem Informasi.\n\nTerima kasih.\n— Admin Akademik Prodi SI",
            'is_active' => true,
        ]);
    }
}
