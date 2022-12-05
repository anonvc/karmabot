<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'address',
        'chainId',
    ];

    protected $appends = ['karma_points'];


    public function getKarmaPointsAttribute()
    { 
      $totalKarma = $this->karmas->sum('points');
      $usedKarma = $this->redemptions->sum('priceInPoints');
      return $totalKarma - $usedKarma;
    }
        
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'walletId');
    }

    public function redemptions()
    {
        return $this->hasMany(Redemption::class,'walletId');
    }

    public function karmas()
    {
        return $this->hasMany(Karma::class,'walletId');
    }


    public function getShortAddressAttribute()
    {
        return \Str::substr($this->address, 0, 6).'...'.\Str::substr($this->address, \Str::length($this->address) - 3, \Str::length($this->address));
    }
}
