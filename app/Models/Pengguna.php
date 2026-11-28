<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status_akun',
        'no_telp',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
