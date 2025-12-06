<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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

    public function scopeByDateRange(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        // 1. Chỉ áp dụng lọc nếu có ít nhất một tham số được truyền vào
        if ($from || $to) {

            // Logic lọc y hệt như bạn viết trong Scope Popular
            if ($from && !$to) {
                // Ví dụ: Review::byDateRange('2024-01-01')->get()
                $query->where("created_at", ">=", $from);
            } elseif (!$from && $to) {
                // Ví dụ: Review::byDateRange(null, '2024-03-01')->get()
                $query->where("created_at", "<=", $to);
            } elseif ($from && $to) {
                // Ví dụ: Review::byDateRange('2024-01-01', '2024-03-01')->get()
                $query->whereBetween("created_at", [$from, $to]);
            }
        }

        return $query; // Trả về Builder để nối tiếp (nếu cần)
    }
}
