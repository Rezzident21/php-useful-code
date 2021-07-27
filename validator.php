<?php


namespace app\components;

class Validator
{
    /**
     * properties in which saving arr errors

     */
    private array $_errors = [];

    /**
     * Method of validation
     */
    public function validate($src, $rules = [], $attribute = [])
    {
        foreach ($src as $item => $item_value) {
            if (key_exists($item, $rules)) {
                foreach ($rules[$item] as $rule => $rule_value) {
                    if (is_int($rule)) {
                        $rule = $rule_value;
                    }

                    // shielding data
                    $item_value = escapeFunction($item_value);

                    switch ($rule) {
                        case 'required':
                            if (empty($item_value) && $rule_value) {
                                $this->addError($item,  ucwords($attribute[$item]) . ' necessarily');
                            }
                            break;
                        case 'minLen':
                            if (mb_strlen($item_value) < $rule_value) {
                                $this->addError($item, ucwords($attribute[$item]) . ' need have  at least ' . $rule_value . ' symbols');
                            }
                            break;
                        case 'maxLen':
                            if (mb_strlen($item_value) > $rule_value) {
                                $this->addError($item, ucwords($attribute[$item]) . ' need max   ' . $rule_value . ' symbols');
                            }
                            break;
                        case 'email':
                            if (!filter_var($item_value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($item, ucwords($attribute[$item]) . ' is not E-mail ');
                            }
                    }
                }
            }
        }
    }

    /**
     * Метод добавляет ошибку
     */
    private function addError($item, $error)
    {
        $this->_errors[$item][] = 'Поле ' . $error;
    }

    /**
     * Method return errors
     */
    public function error()
    {
        if (empty($this->_errors)) {
            return false;
        }

        return $this->_errors;
    }
}
