<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Rayon;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setup roles.
        $roleSuperAdmin = Role::create(['name' => 'super_admin']);
        $roleAdminUP3 = Role::create(['name' => 'admin_up3']);
        $roleAdminULP = Role::create(['name' => 'admin_ulp']);
        $roleVerifikator = Role::create(['name' => 'user_verifikator']);
        $roleLapangan = Role::create(['name' => 'user_lapangan']);

        // Data wilayah.
        $wilayah = Wilayah::create([
            'kode' => 'WRKR',
            'nama' => 'Wilayah Riau dan Kepulauan Riau'
        ]);

        // Data area.
        $area = Area::create([
            'wilayah_id' => $wilayah->id,
            'kode' => '6414',
            'nama' => 'UP3 Rengat'
        ]);

        // Data rayon.
        $rayons = [
            ['kode_rayon' => '18410', 'nama' => 'ULP Rengat Kota'],
            ['kode_rayon' => '18420', 'nama' => 'ULP Taluk Kuantan'],
            ['kode_rayon' => '18430', 'nama' => 'ULP Air Molek'],
            ['kode_rayon' => '18440', 'nama' => 'ULP Tembilahan'],
            ['kode_rayon' => '18450', 'nama' => 'ULP Kuala Enok'],
        ];

        foreach ($rayons as $data) {
            Rayon::create([
                'area_id' => $area->id,
                'kode_rayon' => $data['kode_rayon'],
                'nama' => $data['nama'],
            ]);
        }

        // Generate Super Admin
        $superAdmin = User::create([
            'rayon_id' => null,
            'identity_number' => '640001',
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'username' => 'super.admin',
            'password' => Hash::make('password123'),
        ]);

        $superAdmin->assignRole('super_admin');

        // Generate Admin Area
        $adminArea = User::create([
            'rayon_id' => null,
            'identity_number' => '641401',
            'name' => 'Admin UP3 Rengat',
            'email' => 'admin.up3rengat@example.com',
            'username' => 'admin.up3rengat',
            'password' => Hash::make('password123'),
        ]);

        $adminArea->assignRole('admin_up3');

        // Generate Admin Rayon
        $targetRayon = Rayon::where('kode_rayon', '18410')->first();

        $adminRengat = User::create([
            'rayon_id' => $targetRayon->id,
            'name' => 'Admin Rengat Kota',
            'email' => 'admin.ulprengat@example.com',
            'username' => 'admin.rengat',
            'password' => Hash::make('password123'),
        ]);

        $adminRengat->assignRole('admin_ulp');
        $this->command->info('Seeding selesai! Super Admin created with ID: ' . $superAdmin->fresh()->identity_number);
    }
}
