<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    // Property yang digunakan untuk menyimpan nama-nama yang bisa diisi valuenya
    protected $fillable = [
        'type',
        'name',
        'price',
        'stock',
    ];
}
