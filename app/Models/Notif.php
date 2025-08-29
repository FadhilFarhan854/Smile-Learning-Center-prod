<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\SPP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Notif extends Model
{
    use HasFactory;
	
	/**
     * Get the modul that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
	
	/**
     * Get the modul that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
	
	public function spp(): BelongsTo
    {
        return $this->belongsTo(SPP::class, 'spp_id');
    }
}
