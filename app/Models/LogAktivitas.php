<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'aksi',
        'waktu'
    ];

    protected $casts = [
        'waktu' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public static function log($aksi, $userId = null)
    {
        return self::create([
            'id_user' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'waktu' => now()
        ]);
    }
}