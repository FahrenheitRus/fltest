<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InnRule implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        //Проверка валидности ИНН

        $len = strlen($value);

        if ($len == 10) {
            //Юр лицо
            if (preg_match('#([\d]{10})#', $value, $m)) {
                $value = $m[0];
                $code10 = (($value[0] * 2 + $value[1] * 4 + $value[2] *10 + $value[3] * 3 +
                            $value[4] * 5 + $value[5] * 9 + $value[6] * 4 + $value[7] * 6 +
                            $value[8] * 8) % 11 ) % 10;
                if ($code10 == $value[9]) return $value;
            }
        } elseif ($len == 12) {
            //Физ.лица и ИП - 12 знаков
            if (preg_match('#([\d]{12})#', $value, $m)) {
                $value = $m[0];
                $code11 = (($value[0] * 7 + $value[1] * 2 + $value[2] * 4 + $value[3] *10 +
                            $value[4] * 3 + $value[5] * 5 + $value[6] * 9 + $value[7] * 4 +
                            $value[8] * 6 + $value[9] * 8) % 11 ) % 10;
                $code12 = (($value[0] * 3 + $value[1] * 7 + $value[2] * 2 + $value[3] * 4 +
                            $value[4] *10 + $value[5] * 3 + $value[6] * 5 + $value[7] * 9 +
                            $value[8] * 4 + $value[9] * 6 + $value[10]* 8) % 11 ) % 10;

                if ($code11 == $value[10] && $code12 == $value[11]) return $value;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Указан некорректный ИНН';
    }
}
