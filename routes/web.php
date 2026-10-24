<?php
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FoodCategoryController as AdminFoodCategoryController;
use App\Http\Controllers\Admin\FoodController as AdminFoodController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');
Route::get('/home', [PublicPageController::class, 'home'])->name('home');
Route::get('/gioi-thieu', [PublicPageController::class, 'about'])->name('about');
Route::get('/thuc-pham', [CatalogController::class, 'index'])->name('foods.index');
Route::get('/thuc-pham/{food:slug}', [CatalogController::class, 'show'])->name('foods.show');
// The same account sign-in URL is used by every role. /dashboard routes
// authenticated visitors to the area permitted for their account.
Route::get('/dashboard', function () {
    return auth()->user()->hasRole(config('universaltea.roles.admin'))
        ? redirect()->route('admin.foods.index')
        : redirect()->route('foods.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bookmarked admin login links now lead to the single sign-in page.
// No second login form or admin-specific password endpoint is exposed.
Route::redirect('/adminlogin', '/login', 302);

// Both the role middleware and FormRequest authorization protect all admin writes.
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/foods', [AdminFoodController::class, 'index'])->name('admin.foods.index');
    Route::post('/foods', [AdminFoodController::class, 'store'])->name('admin.foods.store');
    Route::get('/foods/{food}/edit', [AdminFoodController::class, 'edit'])->name('admin.foods.edit');
    Route::put('/foods/{food}', [AdminFoodController::class, 'update'])->name('admin.foods.update');
    Route::delete('/foods/{food}', [AdminFoodController::class, 'destroy'])->name('admin.foods.destroy');

    Route::get('/categories', [AdminFoodCategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [AdminFoodCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [AdminFoodCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [AdminFoodCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [AdminFoodCategoryController::class, 'destroy'])->name('admin.categories.destroy');
});

require __DIR__.'/auth.php';
