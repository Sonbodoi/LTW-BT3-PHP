<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodCategoryRequest;
use App\Models\FoodCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FoodCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => FoodCategory::query()->withCount('foods')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function store(StoreFoodCategoryRequest $request): RedirectResponse
    {
        FoodCategory::create($this->payload($request));
        return back()->with('status', 'Đã tạo danh mục mới.');
    }

    public function edit(FoodCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreFoodCategoryRequest $request, FoodCategory $category): RedirectResponse
    {
        $category->update($this->payload($request, $category));
        return redirect()->route('admin.categories.index')->with('status', 'Đã cập nhật danh mục.');
    }

    public function destroy(FoodCategory $category): RedirectResponse
    {
        // Original FK is CASCADE DELETE: never accidentally delete a whole product category's foods.
        if ($category->foods()->exists()) {
            return back()->withErrors(['category' => 'Danh mục đang chứa sản phẩm. Hãy chuyển sản phẩm sang danh mục khác trước khi xóa.']);
        }
        try {
            $category->delete();
        } catch (QueryException $exception) {
            return back()->withErrors(['category' => 'Không thể xóa danh mục đang có dữ liệu liên kết.']);
        }
        return back()->with('status', 'Đã xóa danh mục.');
    }

    private function payload(StoreFoodCategoryRequest $request, ?FoodCategory $category = null): array
    {
        $name = trim((string) $request->input('name'));
        if ($category && $category->name === $name) {
            $slug = $category->slug;
        } else {
            $base = Str::slug($name) ?: 'danh-muc';
            $slug = $base;
            $number = 2;
            while (FoodCategory::query()->where('slug', $slug)->when($category, fn ($q) => $q->whereKeyNot($category->id))->exists()) {
                $slug = $base.'-'.$number++;
            }
        }
        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $request->input('description') ?: null,
            'sort_order' => $request->integer('sort_order'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
