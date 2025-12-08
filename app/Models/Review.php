<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        "review",
        "rating"
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        static::created(fn(Review $review) => Cache::forget("book:" . $review->book_id));
        static::updated(fn(Review $review) => Cache::forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => Cache::forget("book:" . $review->book_id));
    }
}
