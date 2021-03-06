<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User
{
    use HasFactory;
    protected $table = 'users';

    public $timestamps = false;


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
