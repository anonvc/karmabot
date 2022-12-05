<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'projectId', 
        'walletId', 
        'rewardId',
        'priceInPoints',
        'info',
        'delivered'
    ];


    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'walletId');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class,'rewardId');
    }
    public function project()
    {
        return $this->belongsTo(Project::class,'projectId');
    }

}
