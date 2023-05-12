<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'tallers';
    protected $primaryKey = 'codi';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'descripcio',
        'creador',
        'adrecat',
        'nalumnes',
        'materials',
        'espai',
        'ajudants'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'codi'
    ];

    

    public function user()
    {
        return $this->belongsTo('App\Models\Usuari', 'creador');
    }

    //An user can only create one workshop
    

    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = $value;
    }

    public static function getTallers()
    {
        return Taller::all();
    }

    public static function getTaller($codi)
    {
        return Taller::where('codi', $codi)->first();
    }

    public static function getTallersByUser($email)
    {
        return Taller::where('creador', $email)->get();
    }

    public static function getTallersByParticipant($email)
    {
        return Taller::where('participants', 'LIKE', '%'.$email.'%')->get();
    }

    public static function getTallersByAjudant($email)
    {
        return Taller::where('ajudants', 'LIKE', '%'.$email.'%')->get();
    }


}

?>