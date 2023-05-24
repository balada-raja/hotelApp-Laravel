<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailPemesanan extends Model
{
    use HasFactory;
    protected $table = 'detail_pemesanan';
    protected $primaryKey = 'id_detail_pemesanan';

    protected $fillable = [
        'id_pemesanan', 
        'id_kamar', 
        'tgl_akses', 
        'harga'
    ];

    public function pemesanan(){
        return $this->hasMany(pemesanan::class);
    }
}
