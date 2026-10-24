<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodRequest;
use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'integer', 'exists:food_categories,id'],
            'availability' => ['nullable', 'in:all,available,hidden,out_of_stock'],
        ]);

        $foods = Food::query()->with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));
                $query->where(fn ($q) => $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('short_description', 'like', '%'.$term.'%'));
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->query('availability') === 'available', fn ($query) => $query->where('is_available', true)->where('stock', '>', 0))
            ->when($request->query('availability') === 'hidden', fn ($query) => $query->where('is_available', false))
            ->when($request->query('availability') === 'out_of_stock', fn ($query) => $query->where('stock', 0))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.foods.index', [
            'foods' => $foods,
            'categories' => FoodCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreFoodRequest $request): RedirectResponse
    {
        Food::create($this->payloadFromRequest($request));
        return redirect()->route('admin.foods.index')->with('status', 'Đã tạo món mới.');
    }

    public function edit(Food $food): View
    {
        return view('admin.foods.edit', [
            'food' => $food,
            'categories' => FoodCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(StoreFoodRequest $request, Food $food): RedirectResponse
    {
        $oldImage = $food->image_path;
        $food->update($this->payloadFromRequest($request, $food));
        if ($oldImage && $oldImage !== $food->image_path && str_starts_with($oldImage, 'food-images/')) {
            Storage::disk('public')->delete($oldImage);
        }
        return redirect()->route('admin.foods.index')->with('status', 'Đã cập nhật món ăn.');
    }

    public function destroy(Food $food): RedirectResponse
    {
        // orders.food_id has RESTRICT DELETE in the original schema: show a friendly message.
        if ($food->orders()->exists()) {
            return back()->withErrors(['food' => 'Không thể xóa món đã có đơn hàng liên kết. Hãy tắt trạng thái Đang bán để ẩn sản phẩm.']);
        }
        $path = $food->image_path;
        try {
            $food->delete();
        } catch (QueryException $exception) {
            return back()->withErrors(['food' => 'Không thể xóa sản phẩm vì đang có dữ liệu liên kết. Hãy tạm ẩn sản phẩm.']);
        }
        if ($path && str_starts_with($path, 'food-images/')) {
            Storage::disk('public')->delete($path);
        }
        return back()->with('status', 'Đã xóa món ăn.');
    }

    private function payloadFromRequest(StoreFoodRequest $request, ?Food $food = null): array
    {
        $imagePath = $food?->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs(
                'food-images', (string) Str::uuid().'.'.$request->file('image')->guessExtension(), 'public'
            );
        }
        // Keep the slug stable when editing without changing the name.
        $name = trim((string) $request->input('name'));
        if ($food && $food->name === $name) {
            $slug = $food->slug;
        } else {
            $slugBase = Str::slug($name) ?: 'san-pham';
            $slug = $slugBase;
            $counter = 2;
            while (Food::query()->where('slug', $slug)->when($food, fn ($q) => $q->whereKeyNot($food->id))->exists()) {
                $slug = $slugBase.'-'.$counter++;
            }
        }
        return [
            'category_id' => $request->integer('category_id'),
            'name' => $name,
            'slug' => $slug,
            'short_description' => $request->input('short_description') ?: null,
            'description' => $request->input('description') ?: null,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'image_path' => $imagePath,
        ];
    }
}
