<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'chainId',
        'userId',
        'name',
        'image',
        'collection_uid',
        'discord_guild_id',
        'discord_channel_id',
        'karmaValue',
        'lastRefreshed',
    ];

    protected $casts = [
        'lastRefreshed' => 'datetime',
    ];

    public function chain()
    {
        return $this->belongsTo(Chain::class,'chainId');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class,'projectId');
    }

    
    public function redemptions()
    {
        return $this->hasMany(Redemption::class,'projectId');
    }
}
