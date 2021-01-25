<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantFee extends Model
{
    protected $guarded = ["id"];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_fee', 'transaction_counts',
    ];
}
