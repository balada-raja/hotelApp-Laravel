<?php

namespace App\Models;

use App\Models\kamar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipeKamar extends Model
{
    use HasFactory;
    protected $table = 'tipe_kamar';
    protected $primaryKey = 'id_tipe_kamar';

    protected $fillable = [
        'nama_kamar', 
        'deskripsi', 
        'harga', 
        'foto'
    ];

    public function kamar(){
        return $this->hasMany(kamar::class);
    }
}
