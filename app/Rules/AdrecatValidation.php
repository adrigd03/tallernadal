<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class AdrecatValidation implements Rule
{
    public function passes($attribute, $value)
    {
        $allowedValues = ['1er ESO', '2n ESO', '3er ESO', '4rt ESO', '1er SMX', '2n SMX', '1er FPB', '2n FPB', '1er BAT', '2n BAT'];

        //value is an array of values that are selected in the form and we need to check if all of them are in the allowed values
        foreach ($value as $val) {
            if (!in_array($val, $allowedValues)) {
                return false;
            }
        }
        return true;
    }
    
    public function message()
    {
        $allowedValues = ['1er ESO', '2n ESO', '3er ESO', '4rt ESO', '1er SMX', '2n SMX', '1er FPB', '2n FPB', '1er BAT', '2n BAT'];

        return "Els cursos que és poden adreçar són aquests " . implode(', ', $allowedValues);
    }
}



?>