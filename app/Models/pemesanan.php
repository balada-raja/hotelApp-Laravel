<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemesanan extends Model
{
    use HasFactory;
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'nomor_pemesanan', 
        'nama_pemesan', 
        'email_pemesan', 
        'tgl_pemesanan', 
        'tgl_check_in',
        'tgl_check_out',
        'nama_tamu',
        'jumlah_kamar',
        'id_tipe_kamar',
        'status_pemesanan',
        'id_user'
    ];

    public function detailPemesanan(){
        return $this->belongsTo(detailPemesanan::class, 'id_detail_pemesanan', 'id_detail_pemesanan');
    }
}
