<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modul extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori', 
        'level',
        'status',
        'stock'
    ];

    /**
     * Get all of the order for the Modul
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function countStock()
    {
        /* $orderCounts = Order::groupBy('modul_id')
        ->selectRaw('modul_id, COUNT(*) as count')
        ->with('modul') // Eager load the related modul
        ->get();

        $orderCount = $orderCounts->where('modul_id', $this->id)->first();

        // Check if $orderCount is not null before accessing its properties
        if ($orderCount) {
            $stock = $this->stock - $orderCount->count;
            return $stock;
        } else {
            // If there are no orders for the modul, the remaining stock is the initial stock
            return $this->stock;
        }; */

        return $this->stock - $this->order_count;

    }
}
