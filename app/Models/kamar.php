<?php

namespace App\Models;

use App\Models\tipeKamar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kamar extends Model
{
    use HasFactory;
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'nomor', 
        'id_tipe_kamar'
    ];

    public function tipe_kamar(){
        return $this->belongsTo(tipeKamar::class, 'id_tipe_kamar', 'id_tipe_kamar');
    }
}
