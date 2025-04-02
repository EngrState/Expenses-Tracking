<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
    RequiredRules,
    EmailRule,
    MinRule,
    InRule, DateFormatRule,
    UrlRule, NumericRule,
    MatchRule, LengthMaxRule
};



// use App\Config\Paths;


class ValidatorServices
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->add('required', new RequiredRules());
        $this->validator->add('email', new EmailRule());
        $this->validator->add('min', new MinRule());
        $this->validator->add('in', new InRule());
        $this->validator->add('url', new UrlRule());
        $this->validator->add('match', new MatchRule());
        $this->validator->add('lengthMax', new LengthMaxRule());
        $this->validator->add('numeric', new NumericRule());
        $this->validator->add('dateFormat', new DateFormatRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'age' => ['required', 'min:18'],
            'country' => ['required', 'in:invalid'],
            'socialMediaURL' => ['required', 'url'],
            'password' => ['required'],
            'cpassword' => ['required', 'match:password'],
            'tos' => ['required']
        ]);
    }
    public function validateLogin(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }
    public function validateTransaction(array $formData)
    {
        $this->validator->validate($formData, [
            'description' => ['required', 'lengthMax:255'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'dateFormat:Y-m-d'],
        ]);
    }
    
}
