<?php
namespace Database\Seeders;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => config('universaltea.roles.admin'), 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => config('universaltea.roles.customer'), 'guard_name' => 'web']);

        $admin = User::firstOrCreate(['email' => 'admin@universaltea.test'], [
            'full_name' => 'Universal Tea Admin', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $admin->assignRole($adminRole);
        $user = User::firstOrCreate(['email' => 'test@example.com'], [
            'full_name' => 'Test User', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $user->assignRole($customerRole);

        // The catalog data lives in a dedicated seeder and is safe to rerun (updateOrCreate).
        $this->call(ProductDemoSeeder::class);
    }
}
