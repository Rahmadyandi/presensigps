<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;
    protected $table = 'penelitian';
    protected $primaryKey = 'id_penelitian';
    protected $fillable = [
        'id_dosen',
        'tanggal',
        'bukti',
        'lokasi',
        'verifikasi',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}
