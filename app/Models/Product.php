<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'category_id', 'in_stock', 'rating'
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float'
    ];

    protected static function booted(): void
    {
        static::saving(function ($course) {
            if ($course->rating < 0 || $course->rating > 5) {
                throw new \Exception('Rating must be between 0 and 5');
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->select('id', 'name');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(isset($filters['q']), function ($query) use ($filters) {
                $query->where('name', 'like', "%{$filters['q']}%");
            })
            ->when(isset($filters['price_from']), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['price_from']);
            })
            ->when(isset($filters['price_to']), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['price_to']);
            })
            ->when(isset($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['in_stock']), function ($query) use ($filters) {
                $query->where('in_stock', filter_var($filters['in_stock'], FILTER_VALIDATE_BOOLEAN));
            })
            ->when(isset($filters['rating_from']), function ($query) use ($filters) {
                $query->where('rating', '>=', $filters['rating_from']);
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                $this->applySort($query, $filters['sort']);
            });
    }

    protected function applySort(Builder $query, string $sort): void
    {
        $sortOptions = [
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'rating_desc' => ['rating', 'desc'],
            'newest' => ['created_at', 'desc'],
        ];

        if (isset($sortOptions[$sort])) {
            [$column, $direction] = $sortOptions[$sort];
            $query->orderBy($column, $direction);
        }
    }

}
