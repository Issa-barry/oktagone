<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer les rôles et permissions de base.
     */
    public function run(): void
    {
        // =============================
        // 🧩 Création des rôles
        // =============================
        $roles = [
            'oktagone.superadmin',
            'oktagone.operator',
            'pos.admin',
            'transfer.admin',
            'fx.admin',
            'investment.admin',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // =============================
        // 🔐 Permissions (exemples de base)
        // =============================
        $permissions = [
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            'pos.view',
            'pos.manage',

            'transfer.view',
            'transfer.create',
            'transfer.validate',

            'fx.view',
            'fx.update.taux',

            'investment.view',
            'investment.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm);
        }

        // =============================
        // 🔗 Attribution de permissions
        // =============================
        $superadmin = Role::where('name', 'oktagone.superadmin')->first();
        $operator   = Role::where('name', 'oktagone.operator')->first();

        // Superadmin → toutes les permissions
        if ($superadmin) {
            $superadmin->syncPermissions(Permission::all());
        }

        // Operator → accès limité
        if ($operator) {
            $operator->syncPermissions([
                'user.view',
                'transfer.view',
                'pos.view',
            ]);
        }
    }


    // php artisan db:seed --class=RoleSeeder

}
