<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

class Kobong extends Model
{
    use BelongsToPesantren;
    protected $table = 'kobong';
    
    protected $fillable = [
        'pesantren_id',
        'asrama_id',
        'nama_kobong',
        'nomor_kobong',
    ];
    
    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }
    
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
}
