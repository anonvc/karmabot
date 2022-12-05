<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;


    protected $fillable = [
        'projectId',
        'name',
        'description',
        'icon', 
        'priceInPoints', 
        'inventory',
        'redemptionInfo'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'projectId');
    }
    public function redemptions()
    {
        return $this->hasMany(Redemption::class,'rewardId');
    }
}
