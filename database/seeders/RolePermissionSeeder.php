<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'user.manage',

            'product.view',
            'product.create',
            'product.edit',
            'product.delete',
            'product.import',
            'product.export',

            'raw_material.view',
            'raw_material.create',
            'raw_material.edit',
            'raw_material.delete',
            'raw_material.import',
            'raw_material.export',

            'supplier.view',
            'supplier.create',
            'supplier.edit',
            'supplier.delete',
            'supplier.import',
            'supplier.export',

            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            'unit.view',
            'unit.create',
            'unit.edit',
            'unit.delete',

            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',

            'order.view',
            'order.create',
            'order.update',
            'order.cancel',
            'order.print',

            'purchase.view',
            'purchase.create',
            'purchase.edit',
            'purchase.approve',
            'purchase.delete',
            'purchase.report',

            'quotation.view',
            'quotation.create',
            'quotation.complete',
            'quotation.delete',

            'nota_receipt.view',
            'nota_receipt.print',

            'report.view',
            'report.print',
            'report.export',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $owner = Role::findOrCreate('owner', 'web');
        $manager = Role::findOrCreate('manager_pic', 'web');
        $admin = Role::findOrCreate('admin', 'web');

        $owner->syncPermissions($permissions);

        $manager->syncPermissions([
            'product.view',
            'product.create',
            'product.edit',
            'product.import',
            'product.export',
            'raw_material.view',
            'raw_material.create',
            'raw_material.edit',
            'raw_material.import',
            'raw_material.export',
            'supplier.view',
            'supplier.create',
            'supplier.edit',
            'supplier.import',
            'supplier.export',
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',
            'unit.view',
            'unit.create',
            'unit.edit',
            'unit.delete',
            'purchase.view',
            'purchase.create',
            'purchase.report',
            'quotation.view',
            'quotation.create',
            'quotation.complete',
            'report.view',
            'report.print',
            'report.export',
        ]);

        $admin->syncPermissions([
            'product.view',
            'product.export',
            'raw_material.view',
            'raw_material.export',
            'supplier.view',
            'supplier.export',
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',
            'unit.view',
            'unit.create',
            'unit.edit',
            'unit.delete',
            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',
            'order.view',
            'order.create',
            'order.update',
            'order.cancel',
            'order.print',
            'nota_receipt.view',
            'nota_receipt.print',
            'report.view',
            'report.print',
            'report.export',
        ]);

        User::query()
            ->whereIn('level', ['owner', 'manager_pic', 'admin'])
            ->each(fn (User $user) => $user->syncRoles($user->level));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
