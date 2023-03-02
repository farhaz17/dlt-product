<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'status', 'type', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::updating(function ($product) {
            $changes = $product->getDirty();
            $user_id = auth()->id();
            foreach ($changes as $field => $new_value) {
                $old_value = $product->getOriginal($field);
                Log::info('This is the change in update');
                Log::channel('update')->info([
                    'model' => get_class($product),
                    'field' => $field,
                    'old_value' => $old_value,
                    'new_value' => $new_value,
                    'user_id' => $user_id,
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ]);
            }
        });
    }
}
