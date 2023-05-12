<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Taller;


class Usuari extends Model implements Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuaris';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    protected $fillable = [
        'nom',
        'cognoms',
        'email',
        'etapa',
        'curs',
        'grup',
        'admin',
        'superadmin',
    ];

    //modify user admin
    public function setAdminAttribute($value)
    {
        $this->attributes['admin'] = $value;
    }

    //modify user data (name, surname, email, stage, course, group)
    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = $value;
    }

    public function setCognomsAttribute($value)
    {
        $this->attributes['cognoms'] = $value;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
    }

    public function setEtapaAttribute($value)
    {
        $this->attributes['etapa'] = $value;
    }

    public function setCursAttribute($value)
    {
        $this->attributes['curs'] = $value;
    }

    public function setGrupAttribute($value)
    {
        $this->attributes['grup'] = $value;
    }

    //the above function in one function
    public function setUserDataAttribute($value)
    {
        $this->attributes['nom'] = $value[0];
        $this->attributes['cognoms'] = $value[1];
        $this->attributes['email'] = $value[2];
        $this->attributes['etapa'] = $value[3];
        $this->attributes['curs'] = $value[4];
        $this->attributes['grup'] = $value[5];
    }
    
    //get user data
    public function getAuthIdentifier()
    {
        return $this->email;
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function tallers(){
        return $this->hasMany('App\Models\Taller', 'creador');
    }

    public static function participacio($user){
        // contem el numero de vegades que surt el usuari en el camp de participants de la taula de tallers
        return Taller::select('participants')->where('participants', 'like', '%'.$user->email.'%')->count() ;
    }
    // check if the user has created a taller
    public static function getCreadors($user){
        return Taller::select('creador')->where('creador', 'like', '%'.$user->email.'%')->count() ;
    }

}

?>
