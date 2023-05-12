<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Usuari;

class UserAjudant  implements Rule
{
    public function passes($attribute, $value)
    {
        //Comprovem que els usuaris que s'assignin com a ajudants existeixin
        $value = explode(',', $value);
        if($value[0] == null){
            return true;
        }
        foreach ($value as $usuari) {
            if (!Usuari::where('email', $usuari)->exists()) {
                return false;
            }
        }
        return true;

    }
    
    public function message()
    {
        return 'Algun dels usuaris que has introduït no existeix';

    }
}



?>