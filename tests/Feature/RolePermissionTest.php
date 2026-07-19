<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_roles_follow_the_thesis_access_matrix(): void
    {
        $owner = Role::findByName('owner');
        $manager = Role::findByName('manager_pic');
        $admin = Role::findByName('admin');

        $this->assertTrue($owner->hasPermissionTo('user.manage'));
        $this->assertTrue($owner->hasPermissionTo('purchase.approve'));

        $this->assertTrue($manager->hasPermissionTo('product.create'));
        $this->assertTrue($manager->hasPermissionTo('purchase.create'));
        $this->assertFalse($manager->hasPermissionTo('purchase.approve'));
        $this->assertFalse($manager->hasPermissionTo('order.create'));

        $this->assertTrue($admin->hasPermissionTo('order.create'));
        $this->assertTrue($admin->hasPermissionTo('nota_receipt.print'));
        $this->assertFalse($admin->hasPermissionTo('product.create'));
        $this->assertFalse($admin->hasPermissionTo('purchase.view'));
    }

    public function test_owner_can_manage_users_but_other_roles_cannot(): void
    {
        $owner = $this->userWithRole('owner');
        $manager = $this->userWithRole('manager_pic');

        $this->actingAs($owner)->get(route('users.index'))->assertOk();
        $this->actingAs($manager)->get(route('users.index'))->assertForbidden();
    }

    public function test_admin_and_manager_are_limited_to_their_workflows(): void
    {
        $admin = $this->userWithRole('admin');
        $manager = $this->userWithRole('manager_pic');

        $this->actingAs($admin)->get(route('products.index'))->assertOk();
        $this->actingAs($admin)->get(route('products.create'))->assertForbidden();
        $this->actingAs($admin)->get(route('orders.index'))->assertOk();
        $this->actingAs($admin)->get(route('purchases.index'))->assertForbidden();

        $this->actingAs($manager)->get(route('products.create'))->assertOk();
        $this->actingAs($manager)->get(route('purchases.index'))->assertOk();
        $this->actingAs($manager)->get(route('orders.index'))->assertForbidden();
        $this->actingAs($manager)
            ->post(route('purchases.update', 'not-found'))
            ->assertForbidden();
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['level' => $role]);
        $user->assignRole($role);

        return $user;
    }
}
