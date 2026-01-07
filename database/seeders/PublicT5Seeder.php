<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class PublicT5Seeder extends Seeder
{
    public function run(): void
    {
        // --- Tickets (APPROVED + ACTIVE) -> HARUS MUNCUL di homepage
        Ticket::create([
            'name' => 'Tiket Pantai Carita',
            'price' => 25000,
            'description' => 'Tiket masuk kawasan Pantai Carita. Cocok untuk liburan santai dan sunset.',
            'visit_date' => now()->addDays(7)->toDateString(),
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'APPROVED',
        ]);

        Ticket::create([
            'name' => 'Tiket Curug Putri',
            'price' => 15000,
            'description' => 'Akses wisata Curug Putri. Siapkan alas kaki nyaman untuk trekking ringan.',
            'visit_date' => null, // untuk test badge "Tanggal belum tersedia"
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'APPROVED',
        ]);

        // --- Tickets (NON APPROVED/ACTIVE) -> TIDAK BOLEH MUNCUL
        Ticket::create([
            'name' => 'Tiket Uji Coba (Pending)',
            'price' => 10000,
            'description' => 'Data uji untuk memastikan filter bekerja (PENDING).',
            'visit_date' => now()->addDays(14)->toDateString(),
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'PENDING',
        ]);

        Ticket::create([
            'name' => 'Tiket Ditolak (Rejected)',
            'price' => 10000,
            'description' => 'Data uji untuk memastikan filter bekerja (REJECTED).',
            'visit_date' => now()->addDays(21)->toDateString(),
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'REJECTED',
        ]);

        // --- Tours (APPROVED + ACTIVE) -> HARUS MUNCUL di homepage
        Tour::create([
            'name' => 'Tour 2D1N Ujung Kulon',
            'price_per_person' => 550000,
            'description' => 'Paket tur singkat ke area Ujung Kulon dengan highlight spot alam.',
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(11)->toDateString(),
            'guide_name' => 'Bang Dika',
            'itinerary' => "Hari 1:\n- Kumpul titik temu\n- Perjalanan menuju lokasi\n- Explore spot utama\n\nHari 2:\n- Sunrise & sarapan\n- Kembali ke titik temu",
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'APPROVED',
        ]);

        Tour::create([
            'name' => 'Tour One Day Trip Baduy (Soon)',
            'price_per_person' => 300000,
            'description' => 'One day trip, cocok untuk yang ingin experience budaya dan trekking ringan.',
            'start_date' => null, // untuk test badge tanggal belum tersedia + tombol Pesan disabled
            'end_date' => null,
            'guide_name' => null,
            'itinerary' => "Rencana itinerary akan diumumkan menyusul.",
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'APPROVED',
        ]);

        // --- Tours (NON APPROVED/ACTIVE) -> TIDAK BOLEH MUNCUL
        Tour::create([
            'name' => 'Tour Draft (Pending)',
            'price_per_person' => 200000,
            'description' => 'Data uji untuk filter (PENDING).',
            'start_date' => now()->addDays(30)->toDateString(),
            'end_date' => now()->addDays(31)->toDateString(),
            'guide_name' => 'Kak Rani',
            'itinerary' => "Draft itinerary.",
            'image_path' => null,
            'is_active' => true,
            'approval_status' => 'PENDING',
        ]);
    }
}
