<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CnpjRuleValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function cnpjValidation($value){
        $cnpj = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cnpj) != 14) {
            return false;
        }

        for ($x=0; $x<10; $x++) {
            if ( $cnpj == str_repeat($x, 14) ) {
                return false;
            }
        }

        for ($t = 12; $t < 14; $t ++) {
            $d = 0;
            $c = 0;
            for ($m = $t - 7; $m >= 2; $m --, $c ++) {
                $d += $cnpj[$c] * $m;
            }
            for ($m = 9; $m >= 2; $m --, $c ++) {
                $d += $cnpj[$c] * $m;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                return false;
                //break;
            }
        }

        return true;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->cnpjValidation($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'CNPJ inválido';
    }
}
