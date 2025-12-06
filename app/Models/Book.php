<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder | QueryBuilder
    {
        return $query->where("title", "like", "%{$title}%");
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withCount([
            "reviews" => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])->orderBy("reviews_count", "desc");
    }

    // public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    // {
    //     // BỘ LỌC 1: Dùng withCount để đếm (Vẫn cần để sắp xếp)
    //     $query->withCount([
    //         "reviews" => fn($q) => $this->dateRangeFilter($q, $from, $to)
    //     ]);

    //     // 👇 BỘ LỌC 2 (MỚI): Dùng with để tải dữ liệu đã được lọc
    //     $query->with([
    //         // Chỉ tải những reviews đã được lọc theo ngày tháng
    //         "reviews" => fn($q) => $this->dateRangeFilter($q, $from, $to)
    //     ])->limit(3);

    //     // Sắp xếp theo số đếm đã tính
    //     return $query->orderBy("reviews_count", "desc");
    // }

    private function dateRangeFilter($query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where("created_at", ">=", $from);
        } elseif (!$from && $to) {
            $query->where("created_at", "<=", $to);
        } elseif ($from && $to) {
            $query->whereBetween("created_at", [$from, $to]);
        }
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withAvg([
            "reviews" => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], "rating")
            ->orderBy("reviews_avg_rating", "desc");
    }

    public function scopeMinReview(Builder $query, int $minReviews): Builder | QueryBuilder
    {
        return $query->having("reviews_count", ">=", $minReviews);
    }

    // public function scopeHighestRated(Builder $query, $from = null, $to = null)
    // {
    //     $query->withCount([
    //         "reviews" => fn($q) => $this->dateRangeFilter($q, $from, $to)
    //     ]);

    //     $query->withAvg([
    //         "reviews" => fn($q) => $this->dateRangeFilter($q, $from, $to)
    //     ], "rating");

    //     // 👇 BỘ LỌC 2 (MỚI): Dùng with để tải dữ liệu đã được lọc
    //     $query->with([
    //         // Chỉ tải những reviews đã được lọc theo ngày tháng
    //         "reviews" => fn($q) => $this->dateRangeFilter($q, $from, $to)
    //     ])->limit(2);

    //     // Sắp xếp theo số đếm đã tính
    //     return $query->orderBy("reviews_count", "desc");
    // }
}
