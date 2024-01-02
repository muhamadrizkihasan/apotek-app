<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Opsional
    // protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'medicines',
        'name_customer',
        'total_price',
    ];

    // Penegasan tipe data dari migration (hasil property ini ketika diambil / diinsert/update dalam bentuk tipe data apa)
    protected $casts = [
        'medicines' => 'array',
    ];

    public function user()
    {
        // Menghubungkan ke primary key nya
        // Dalam kurung merupakan nama model tempat penyimpanan dari PK nya si FK yang ada di model ini
        return $this->belongsTo(User::class);
    }
}
