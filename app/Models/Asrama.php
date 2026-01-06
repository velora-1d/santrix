<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

class Asrama extends Model
{
    use BelongsToPesantren;
    protected $table = 'asrama';
    
    protected $fillable = [
        'pesantren_id',
        'nama_asrama',
        'gender',
    ];
    
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
    
    public function kobong()
    {
        return $this->hasMany(Kobong::class);
    }
}
