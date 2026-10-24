<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    use HasFactory;
    protected $table = 'foods';
    protected $fillable = ['category_id', 'name', 'slug', 'short_description', 'description', 'price', 'stock', 'is_available', 'is_featured', 'image_path'];
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_available' => 'boolean', 'is_featured' => 'boolean'];
    }

    // Bundled SVGs are always available. If the user downloaded real photos,
    // prefer them without changing existing database records or user uploads.
    public function getImageUrlAttribute(): string
    {
        $path = $this->image_path;
        if (! $path) {
            return asset('images/menu/placeholder.svg');
        }
        if (str_starts_with($path, 'images/menu/')) {
            if (str_ends_with($path, '.svg')) {
                $photoPath = 'images/menu/real/'.basename($path, '.svg').'.jpg';
                if (is_file(public_path($photoPath))) {
                    return asset($photoPath);
                }
            }
            return asset($path);
        }
        return Storage::disk('public')->url($path);
    }

    public function category(): BelongsTo { return $this->belongsTo(FoodCategory::class, 'category_id'); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
}
