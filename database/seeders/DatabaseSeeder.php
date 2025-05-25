<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::table('wallet_transactions')->delete();
        DB::table('recycle_transaction_items')->delete();
        DB::table('recycle_transactions')->delete();
        DB::table('wallets')->delete();
        DB::table('waste_types')->delete();
        DB::table('chats')->delete();
        DB::table('users')->delete();
        DB::table('categories')->delete();
        DB::table('banks')->delete();
        DB::table('articles')->delete();

        DB::table('banks')->insert([
            [
                'name' => 'Bank Sampah Induk Jakarta',
                'address' => 'Jl. Thamrin No. 12, Jakarta Pusat',
                'phone' => '081111222333',
                'latitude' => -6.195027,
                'longitude' => 106.820402,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Sampah Bersih Surabaya',
                'address' => 'Jl. Ahmad Yani No. 20, Surabaya',
                'phone' => '081222333444',
                'latitude' => -7.321917,
                'longitude' => 112.734398,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Sampah Hijau Bandung',
                'address' => 'Jl. Diponegoro No. 30, Bandung',
                'phone' => '081333444555',
                'latitude' => -6.898568,
                'longitude' => 107.617142,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('Pass123@'),
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'profile_picture' => null,
                'bank_id' => null,
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Daur',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('Pass123@'),
                'phone' => '081987654321',
                'address' => 'Jl. Sudirman No. 25, Bandung',
                'profile_picture' => null,
                'bank_id' => null,
                'role' => 'master_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rudy Tabootie',
                'email' => 'rudy@gmail.com',
                'password' => Hash::make('Pass123@'),
                'phone' => '081112233445',
                'address' => 'Jl. Gatot Subroto No. 5, Surabaya',
                'profile_picture' => null,
                'bank_id' => null,
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Admin Jakarta',
                'email' => 'bankadmin@gmail.com',
                'password' => Hash::make('Pass123@'),
                'phone' => '081555666777',
                'address' => 'Jl. Thamrin No. 12, Jakarta Pusat',
                'profile_picture' => null,
                'bank_id' => 1,
                'role' => 'bank_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'Plastik',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kertas',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logam',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kaca',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Organik',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elektronik',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kain',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kayu',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('waste_types')->insert([
            [
                'category_id' => 1,
                'name' => 'Botol PET',
                'unit' => 'kg',
                'price_per_unit' => 5000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Plastik PP',
                'unit' => 'kg',
                'price_per_unit' => 3000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Plastik HDPE',
                'unit' => 'kg',
                'price_per_unit' => 4000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Karton',
                'unit' => 'kg',
                'price_per_unit' => 2000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Kertas HVS',
                'unit' => 'rem',
                'price_per_unit' => 10000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Koran Bekas',
                'unit' => 'rem',
                'price_per_unit' => 7500.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Aluminium',
                'unit' => 'kg',
                'price_per_unit' => 10000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Besi',
                'unit' => 'kg',
                'price_per_unit' => 3000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Botol Kaca',
                'unit' => 'kg',
                'price_per_unit' => 1000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Sisa Makanan',
                'unit' => 'liter',
                'price_per_unit' => 500.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Baterai Bekas',
                'unit' => 'unit',
                'price_per_unit' => 2000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Kain Perca',
                'unit' => 'kg',
                'price_per_unit' => 1500.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 8,
                'name' => 'Kayu Bekas',
                'unit' => 'kg',
                'price_per_unit' => 1000.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('recycle_transactions')->insert([
            [
                'user_id' => 1,
                'bank_id' => 1,
                'pickup_address' => 'Jl. Merdeka No. 10, Jakarta',
                'latitude' => -6.195027,
                'longitude' => 106.820402,
                'appointment_time' => Carbon::create(2025, 5, 20, 10, 0, 0),
                'note' => 'Jemur di pagi hari',
                'method' => 'pickup',
                'status' => 'waiting',
                'total_quantity' => 5.0,
                'total_amount' => 25000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'bank_id' => 2,
                'pickup_address' => 'Jl. Gatot Subroto No. 5, Surabaya',
                'latitude' => -6.195027,
                'longitude' => 106.820402,
                'appointment_time' => Carbon::create(2025, 5, 21, 14, 0, 0),
                'note' => 'Di depan rumah',
                'method' => 'dropoff',
                'status' => 'process',
                'total_quantity' => 10.0,
                'total_amount' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'bank_id' => 3,
                'pickup_address' => 'Jl. Merdeka No. 10, Jakarta',
                'latitude' => -6.195027,
                'longitude' => 106.820402,
                'appointment_time' => Carbon::create(2025, 5, 22, 16, 0, 0),
                'note' => 'Hubungi sebelum ambil',
                'method' => 'pickup',
                'status' => 'success',
                'total_quantity' => 2.0,
                'total_amount' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('recycle_transaction_items')->insert([
            [
                'transaction_id' => 1,
                'waste_type_id' => 1,
                'quantity' => 5.0,
                'sub_total' => 25000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_id' => 2,
                'waste_type_id' => 11,
                'quantity' => 10.0,
                'sub_total' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_id' => 3,
                'waste_type_id' => 6,
                'quantity' => 2.0,
                'sub_total' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('wallets')->insert([
            [
                'user_id' => 1,
                'balance' => 90000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'balance' => 100000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'balance' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('wallet_transactions')->insert([
            [
                'wallet_id' => 1,
                'amount' => 50000.00,
                'method' => null,
                'account_info' => null,
                'type' => 'deposit',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wallet_id' => 1,
                'amount' => 40000.00,
                'method' => null,
                'account_info' => null,
                'type' => 'deposit',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wallet_id' => 2,
                'amount' => 100000.00,
                'method' => 'ovo',
                'account_info' => '081234567890',
                'type' => 'withdrawal',
                'status' => 'waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wallet_id' => 3,
                'amount' => 30000.00,
                'method' => 'bni',
                'account_info' => '9876543210',
                'type' => 'withdrawal',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('articles')->insert([
            [
                'title' => 'Manfaat Daur Ulang',
                'content' => 'Daur ulang membantu mengurangi sampah dan menghemat sumber daya...',
                'image_url' => null,
                'pewarta' => 'Ahmad Yani',
                'editor' => 'Siti Aminah',
                'copyright' => 'Hak cipta dilindungi',
                'sumber' => 'Jurnal Lingkungan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cara Mengelola Sampah Plastik',
                'content' => 'Sampah plastik dapat didaur ulang menjadi produk baru...',
                'image_url' => null,
                'pewarta' => 'Budi Santoso',
                'editor' => 'Siti Aminah',
                'copyright' => 'Hak cipta dilindungi',
                'sumber' => 'Majalah Hijau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pentingnya Daur Ulang Kertas',
                'content' => 'Daur ulang kertas mengurangi penebangan pohon...',
                'image_url' => null,
                'pewarta' => 'Rina Lestari',
                'editor' => 'Siti Aminah',
                'copyright' => 'Hak cipta dilindungi',
                'sumber' => 'Koran Nusantara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('chats')->insert([
            [
                'sender_id' => 1,
                'receiver_id' => 2,
                'message' => 'Halo, saya mau tanya soal daur ulang plastik',
                'image_url' => null,
                'sent_at' => now(),
            ],
            [
                'sender_id' => 3,
                'receiver_id' => 2,
                'message' => 'Apa syarat untuk drop off sampah?',
                'image_url' => null,
                'sent_at' => now(),
            ],
            [
                'sender_id' => 2,
                'receiver_id' => 1,
                'message' => 'Silakan bawa sampah yang sudah dipilah',
                'image_url' => null,
                'sent_at' => now(),
            ],
        ]);
    }
}
