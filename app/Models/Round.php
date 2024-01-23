<?php

namespace App\Models;

use App\ModelFilters\RoundFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory, Filterable;

    protected $table = 'rounds';

    protected $fillable = [
        'id',
        'user_id',
        'payment_id',
        'result',
        'play_status',
        'gift_status',

    ];

    public function modelFilter()
    {
        return $this->provideFilter(RoundFilter::class);
    }


}
