<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'password',
        'role',
        'id_jurusan',
        'fcm_token'
    ];

    protected $hidden = [
        'password',
    ];
    public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan');
}
    public function getKey()
    {
        return $this->id_user;
    }
    
public function getKeyName()
{
    return 'id_user';
}
public function resetPassword($id)
{
    $user = User::findOrFail($id);

    // password default
    $newPassword = '123456';

    $user->update([
        'password' => Hash::make($newPassword)
    ]);

    return back()->with('success', 'Password berhasil direset ke: 123456');
}

}
