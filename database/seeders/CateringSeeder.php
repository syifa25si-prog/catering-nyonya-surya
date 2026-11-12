<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CateringSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Nasi Kotak' => [
                ['Nasi Kotak Ayam Bakar', 35000, 'Nasi putih, ayam bakar bumbu kecap, tumis sayur, sambal, kerupuk, dan buah. Sajian praktis untuk rapat dan acara keluarga.'],
                ['Nasi Kotak Rendang', 45000, 'Nasi putih, rendang sapi, sayur daun singkong, sambal hijau, kerupuk, dan buah. Pilihan lengkap dengan cita rasa Nusantara.'],
                ['Nasi Kotak Ayam Serundeng', 30000, 'Nasi putih, ayam serundeng, oseng tempe, sayur, sambal, dan kerupuk. Kehangatan masakan rumahan dalam satu kotak.']],
            'Prasmanan' => [
                ['Prasmanan Nusantara', 65000, 'Pilihan sajian nasi, ayam kecap, ikan asam manis, capcay, sambal, kerupuk, dan buah untuk acara bersama. Harga per porsi.'],
                ['Prasmanan Istimewa', 85000, 'Nasi putih, rendang sapi, ayam panggang, sup sayur, aneka pelengkap, buah, dan puding. Harga per porsi.']],
            'Snack Box' => [
                ['Snack Box Tradisional', 20000, 'Kombinasi tiga kudapan tradisional dan air mineral, cocok untuk rapat, pengajian, dan pertemuan keluarga.'],
                ['Snack Box Premium', 28000, 'Empat pilihan kudapan manis dan gurih beserta air mineral untuk melengkapi acara Anda.']],
            'Paket Keluarga' => [
                ['Paket Santap Keluarga', 40000, 'Nasi, ayam ungkep, tahu tempe, sayur asem, sambal, dan lalapan. Harga per porsi untuk disesuaikan dengan jumlah anggota keluarga.']],
        ];
        foreach ($catalog as $categoryName => $menus) {
            $category = Category::firstOrCreate(['name' => $categoryName], ['description' => 'Pilihan '.$categoryName.' untuk acara Anda.']);
            foreach ($menus as [$name,$price,$description]) {
                Menu::firstOrCreate(['name' => $name], ['category_id' => $category->id, 'description' => $description, 'price' => $price, 'is_available' => true]);
            }
        }
        if (app()->environment('local', 'testing')) {
            foreach ([['Admin Nyonya Surya', 'admin@nyonyasurya.test', 'admin', 'admin'], ['Pelanggan Demo', 'pelanggan@nyonyasurya.test', null, 'customer']] as [$name,$email,$username,$role]) {
                if (! User::where('email', $email)->exists()) {
                    $user = new User(['name' => $name, 'email' => $email, 'username' => $username, 'password' => Hash::make('SuryaDemo2026!')]);
                    $user->role = $role;
                    $user->save();
                }
            }
        }
    }
}
