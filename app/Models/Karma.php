<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karma extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'walletId',
        'projectId',
        'transactionId',
        'points',
    ];


    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transactionId');
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'walletId');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'projectId');
    }
}
