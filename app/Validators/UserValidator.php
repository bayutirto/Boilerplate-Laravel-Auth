<?php

namespace App\Validators;

class UserValidator extends CommonValidator
{
    protected $rules = [
        'username' => ['max:50'],
        'password' => ['max:225'],
        'new_password' => ['confirmed', 'max:225', 'different:password']
    ];

    protected $messages = [
        'username' => [
            'required' => 'Username is required',
            'max' => 'Username more than :max character'
        ],

        'password' => [
            'required' => 'Password is required',
            'max' => 'Password more than :max character'
        ],

        'new_password' => [
            'required' => 'Password is required',
            'confirmed' => 'Password doesn`t match',
            'max' => 'Password more than :max character',
            'different' => 'New Password cannot be the same as the old password'
        ]
    ];

    protected function login()
    {
        return $this->getRules(['username', 'password'], true);
    }

    protected function password()
    {
        return $this->getRules(['password', 'new_password'], true);
    }
}
