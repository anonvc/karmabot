<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'signature',
        'projectId',
        'chainId', 
        'walletId', 
        'price',
        'royalty',
        'blockTime'
    ];

    protected $casts = [
        'blockTime' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'projectId');
    }

    public function getCurrencyAttribute()
    {
      $currency = "ETH";
      if($this->chainId == 2)
      {
        $currency = "SOL";
      }
      return $currency;
    }
    public function getPriceWithCurrencyAttribute()
    {
        return $this->price.' '.$this->getCurrencyAttribute();
    }
    public function getRoyaltyWithCurrencyAttribute()
    {
        return $this->royalty.' '.$this->getCurrencyAttribute();
    }
    
    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'walletId');
    }


    public function karma()
    {
        return $this->hasOne(Karma::class,'transactionId');
    }

}
