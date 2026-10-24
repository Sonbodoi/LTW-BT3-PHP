<?php
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

function sampleCategory(): FoodCategory
{
    return FoodCategory::create(['name' => 'Trà kiểm thử', 'slug' => 'tra-kiem-thu', 'sort_order' => 1, 'is_active' => true]);
}

function courseAdmin(): User
{
    $admin = User::factory()->create();
    $adminRole = \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
    $admin->assignRole($adminRole);
    return $admin;
}

function courseCustomer(): User
{
    $customer = User::factory()->create();
    $role = \Spatie\Permission\Models\Role::findOrCreate('customer', 'web');
    $customer->assignRole($role);
    return $customer;
}

test('demo seed creates 30 illustrated products and two role accounts', function () {
    $this->seed(DatabaseSeeder::class);
    expect(Food::count())->toBe(30);
    expect(FoodCategory::count())->toBe(5);
    expect(Food::whereNotNull('image_path')->count())->toBe(30);
    expect(User::where('email', 'admin@universaltea.test')->firstOrFail()->hasRole('admin'))->toBeTrue();
    expect(User::where('email', 'test@example.com')->firstOrFail()->hasRole('customer'))->toBeTrue();
});

test('customers cannot reach admin pages or perform writes even by direct url', function () {
    $category = sampleCategory();
    $customer = courseCustomer();
    $this->actingAs($customer)->get(route('admin.foods.index'))->assertForbidden();
    $this->actingAs($customer)->post(route('admin.foods.store'), [
        'category_id' => $category->id, 'name' => 'Không được thêm', 'price' => 10000, 'stock' => 5,
    ])->assertForbidden();
    expect(Food::count())->toBe(0);
});

test('admin can create, update and delete an unused product', function () {
    $category = sampleCategory();
    $admin = courseAdmin();
    $this->actingAs($admin)->post(route('admin.foods.store'), [
        'category_id' => $category->id, 'name' => 'Sữa tươi', 'price' => 35000, 'stock' => 10, 'is_available' => 1,
    ])->assertRedirect(route('admin.foods.index'));
    $food = Food::where('slug', 'sua-tuoi')->firstOrFail();
    $this->actingAs($admin)->put(route('admin.foods.update', $food), [
        'category_id' => $category->id, 'name' => 'Sữa tươi mới', 'price' => 40000, 'stock' => 8,
    ])->assertRedirect(route('admin.foods.index'));
    expect($food->refresh()->name)->toBe('Sữa tươi mới');
    $this->actingAs($admin)->delete(route('admin.foods.destroy', $food))->assertRedirect();
    expect(Food::count())->toBe(0);
});

test('duplicate product names get unique slugs', function () {
    $category = sampleCategory();
    $admin = courseAdmin();
    for ($i=0; $i<2; $i++) {
        $this->actingAs($admin)->post(route('admin.foods.store'), [
            'category_id' => $category->id, 'name' => 'Trà đào', 'price' => 45000, 'stock' => 5,
        ])->assertRedirect(route('admin.foods.index'));
    }
    expect(Food::pluck('slug')->sort()->values()->all())->toBe(['tra-dao', 'tra-dao-2']);
});

test('cannot delete nonempty categories accidentally', function () {
    $category = sampleCategory();
    Food::create(['category_id'=>$category->id,'name'=>'Bánh ngọt','slug'=>'banh-ngot','price'=>15000,'stock'=>5]);
    $this->actingAs(courseAdmin())->delete(route('admin.categories.destroy', $category))->assertSessionHasErrors('category');
    expect(Food::count())->toBe(1);
});

test('public can browse catalog but no order endpoint exists', function () {
    $this->get(route('foods.index'))->assertOk();
    $this->post('/orders', [])->assertNotFound();
    $this->post('/api/orders', [])->assertNotFound();
});
