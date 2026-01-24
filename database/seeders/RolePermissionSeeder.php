<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard',
            // Profile
            'user_profile',
            // User Management
            'user_view',
            'user_create',
            'user_edit',
            'user_delete',
            // Role Management
            'role_view',
            'role_create',
            'role_edit',
            'role_delete',
            // PJU Data
            'pju_view',
            'pju_create',
            'pju_edit',
            'pju_delete',
            'pju_gallery',
            // Trafo Data
            'trafo_view',
            'trafo_create',
            'trafo_edit',
            'trafo_delete',
            'trafo_gallery',
            // Maps Data
            'maps_view',
            // Verifikator
            'verifikator_edit',
            // Reports
            'report_view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // -- Super Admin --
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

        // --== Admin UP3 ==--
        $adminUp3 = Role::firstOrCreate(['name' => 'admin_up3']);
        $adminUp3->givePermissionTo([
            // Dashboard
            'dashboard',
            // Profile
            'user_profile',
            // User Management
            'user_view',
            'user_create',
            'user_edit',
            // PJU Data
            'pju_view',
            'pju_create',
            'pju_edit',
            'pju_delete',
            'pju_gallery',
            // Trafo Data
            'trafo_view',
            'trafo_create',
            'trafo_edit',
            'trafo_delete',
            'trafo_gallery',
            // Maps Data
            'maps_view',
            // Verifikator
            'verifikator_edit',
            // Reports
            'report_view',
        ]);

        // --== Admin ULP ==--
        $adminUlp = Role::firstOrCreate(['name' => 'admin_ulp']);
        $adminUlp->givePermissionTo([
            // Dashboard
            'dashboard',
            // Profile
            'user_profile',
            // User Management
            'user_view',
            'user_create',
            'user_edit',
            // PJU Data
            'pju_view',
            'pju_create',
            'pju_edit',
            'pju_delete',
            'pju_gallery',
            // Trafo Data
            'trafo_view',
            'trafo_create',
            'trafo_edit',
            'trafo_delete',
            'trafo_gallery',
            // Maps Data
            'maps_view',
            // Verifikator
            'verifikator_edit',
            // Reports
            'report_view',
        ]);

        // --== Verifikator ==--
        $verifikator = Role::firstOrCreate(['name' => 'verifikator']);
        $verifikator->givePermissionTo([
            // Dashboard
            'dashboard',
            // Profile
            'user_profile',
            // PJU Data
            'pju_view',
            'pju_gallery',
            // Trafo Data
            'trafo_view',
            'trafo_gallery',
            // Maps Data
            'maps_view',
            // Verifikator
            'verifikator_edit',
            // Reports
            'report_view',
        ]);

        // --== Lapangan ==--
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->givePermissionTo([
            // Dashboard
            'dashboard',
            // Profile
            'user_profile',
            // PJU
            'pju_view',
            'pju_create',
            'pju_edit',
            'pju_delete',
            'pju_gallery',
            // Trafo
            'trafo_view',
            'trafo_create',
            'trafo_edit',
            'trafo_delete',
            'trafo_gallery',
            // Maps Data
            'maps_view'
        ]);
    }
}
