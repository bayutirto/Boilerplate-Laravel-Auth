<?php

namespace App\Validators;

class UserFormValidator extends CommonValidator
{
    protected $rules = [
        'username' => ['max:50'],
        'password' => ['max:225', 'confirmed']
    ];

    protected $messages = [
        'username' => [
            'required' => 'Username is required',
            'max' => 'Username more than :max character'
        ],

        'password' => [
            'required' => 'Password is required',
            'max' => 'Password more than :max character',
            'confirmed' => 'Password doesn`t match'
        ]
    ];

    protected function insert()
    {
        return $this->getRules(['username', 'password'], true);
    }
}
