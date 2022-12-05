<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    use HasFactory;

  
    protected $fillable = [
        'name',
        'slug'
    ];


    public function projects()
    {
        return $this->hasMany(Project::class,'chainId');
    }


    public function getCurrencyAttribute()
    {
      $currency = "ETH";
      if($this->id == 2)
      {
        $currency = "SOL";
      }
      return $currency;
    }
}
