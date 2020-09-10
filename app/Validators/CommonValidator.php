<?php
namespace app\Validators;

use Illuminate\Foundation\Http\FormRequest;

class CommonValidator extends FormRequest
{
    protected $rules = [];
    protected $messages = [];
    protected $groupRules = [];

    public function getGroupRule($type)
    {
        return $this->{$type}();
    }

    public function getRules(Array $fields, $required = false)
    {
        $default = ['bail'];
        if($required) $default[] = 'required';
        else $default[] = 'nullable';

        $rules = [];
        $messages = [];
        foreach ($fields as $field)
        {
            $tempRule = array_merge($default, $this->rules[$field]);
            foreach ($tempRule as $rule)
            {
                if(in_array($rule, ['nullable', 'bail'])) continue;
                $explodeRule = explode(':', $rule);
                $messages["$field.$explodeRule[0]"] = $this->messages[$field][$explodeRule[0]];
            }
            $rules[$field] = $tempRule;
        }
        return ['rules' => $rules, 'messages' => $messages];
    }

    public function formatMessages($messages){
        $errors = [];
        foreach($messages->errors()->messages() as $key => $value)
        {
            $errors[] = ['field' => $key, 'message' => $value[0]];
        }
        return ['message' => 'Validation failed', 'errors' => $errors];
    }
}
?>
