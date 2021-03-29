<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El elemento :attribute debe ser aceptado.',
    'active_url' => 'El elemento :attribute no es una URL valida.',
    'after' => 'El elemento :attribute debe ser una fecha después de :date.',
    'after_or_equal' => 'El elemento :attribute debe ser una fecha después o igual a :date.',
    'alpha' => 'El elemento :attribute solo debe contener letras.',
    'alpha_dash' => 'El elemento :attribute solo debe contener letras, números, guiones y guion bajo.',
    'alpha_num' => 'El elemento :attribute solo debe contener letras y números.',
    'array' => 'El elemento :attribute Debe ser un array.',
    'before' => 'El elemento :attribute debe ser una fecha antes de :date.',
    'before_or_equal' => 'El elemento :attribute debe ser una fecha antes o igual a :date.',
    'between' => [
        'numeric' => 'El elemento :attribute debe estar entre :min y :max.',
        'file' => 'El elemento :attribute debe estar entre :min y :max kilobytes.',
        'string' => 'El elemento :attribute debe estar entre :min y :max characters.',
        'array' => 'El elemento :attribute debe tener entre :min y :max items.',
    ],
    'boolean' => 'El elemento :attribute debe ser verdadero o falso.',
    'confirmed' => 'La verificación de :attribute no coincide.',
    'date' => 'El elemento :attribute no es una fecha valida.',
    'date_equals' => 'El elemento :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El elemento :attribute no coincide con el formato :format.',
    'different' => 'El elemento :attribute y :other deben ser diferentes.',
    'digits' => 'El elemento :attribute debe tener :digits digitos.',
    'digits_between' => 'El elemento :attribute debe estar entre :min y :max digitos.',
    'dimensions' => 'El elemento :attribute tiene dimensiones invalidas.',
    'distinct' => 'El elemento :attribute tiene valores repetidos.',
    'email' => 'El elemento :attribute debe ser un email valido.',
    'ends_with' => 'El elemento :attribute debe terminar en uno de los valores siguientes: :values.',
    'exists' => 'El elemento :attribute seleccionado es invalido.',
    'file' => 'El elemento :attribute debe ser un archivo.',
    'filled' => 'El elemento :attribute debe tener un valor.',
    'gt' => [
        'numeric' => 'El elemento :attribute debe ser mayor a :value.',
        'file' => 'El elemento :attribute debe ser mayor a :value kilobytes.',
        'string' => 'El elemento :attribute debe ser mayor a :value caracteres.',
        'array' => 'El elemento :attribute debe tener mas de :value items.',
    ],
    'gte' => [
        'numeric' => 'El elemento :attribute debe ser mayor o igual a :value.',
        'file' => 'El elemento :attribute debe ser mayor o igual a :value kilobytes.',
        'string' => 'El elemento :attribute debe ser mayor o igual a :value caracteres.',
        'array' => 'El elemento :attribute debe tener :value items o más.',
    ],
    'image' => 'El elemento :attribute debe ser una imagen.',
    'in' => 'El elemento :attribute seleccionado es invalido.',
    'in_array' => 'El elemento :attribute no existe en :other.',
    'integer' => 'El elemento :attribute debe ser un numero entero.',
    'ip' => 'El elemento :attribute debe ser una dirección IP valida.',
    'ipv4' => 'El elemento :attribute debe ser una dirección IPv4 valida.',
    'ipv6' => 'El elemento :attribute debe ser una dirección IPv6 valida.',
    'json' => 'El elemento :attribute debe ser un texto JSON valido.',
    'lt' => [
        'numeric' => 'El elemento :attribute debe ser menor a :value.',
        'file' => 'El elemento :attribute debe ser menor a :value kilobytes.',
        'string' => 'El elemento :attribute debe ser menor a :value caracteres.',
        'array' => 'El elemento :attribute debe tener menos de :value items.',
    ],
    'lte' => [
        'numeric' => 'El elemento :attribute debe ser menor o igual a :value.',
        'file' => 'El elemento :attribute debe ser menor o igual a :value kilobytes.',
        'string' => 'El elemento :attribute debe ser menor o igual a :value caracteres.',
        'array' => 'El elemento :attribute no debe tener mas de :value items.',
    ],
    'max' => [
        'numeric' => 'El elemento :attribute no debe ser mayor a :max.',
        'file' => 'El elemento :attribute no debe ser mayor a :max kilobytes.',
        'string' => 'El elemento :attribute no debe ser mayor a :max caracteres.',
        'array' => 'El elemento :attribute no debe tener mas de :max items.',
    ],
    'mimes' => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El elemento :attribute debe ser al menos :min.',
        'file' => 'El elemento :attribute debe ser al menos :min kilobytes.',
        'string' => 'El elemento :attribute debe ser al menos :min caracteres.',
        'array' => 'El elemento :attribute debe tener al menos :min items.',
    ],
    'not_in' => 'El elemento :attribute seleccionado es invalido.',
    'not_regex' => 'El formato del elemento :attribute es invalido.',
    'numeric' => 'El elemento :attribute debe ser un número.',
    'password' => 'El elemento contraseña es incorrecto.',
    'present' => 'El elemento :attribute debe estar presente.',
    'regex' => 'El formato del elemento :attribute es invalido.',
    'required' => 'El elemento :attribute es requerido.',
    'required_if' => 'El elemento :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El elemento :attribute es requerido a menos que :other sea :values.',
    'required_with' => 'El elemento :attribute es requerido cuando :values esta presente.',
    'required_with_all' => 'El elemento :attribute  es requerido cuando :values estan presente.',
    'required_without' => 'El elemento :attribute es requerido cuando :values no esta presente.',
    'required_without_all' => 'El elemento :attribute es requerido cuando :values no estan presentes.',
    'same' => 'El elemento :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El elemento :attribute debe ser :size.',
        'file' => 'El elemento :attribute debe ser de :size kilobytes.',
        'string' => 'El elemento :attribute debe ser de :size caracteres.',
        'array' => 'El elemento :attribute debe contener :size items.',
    ],
    'starts_with' => 'El elemento :attribute debe comenzar con uno de los siguientes valores: :values.',
    'string' => 'El elemento :attribute debe ser solo texto.',
    'timezone' => 'El elemento :attribute debe ser una zona valida.',
    'unique' => 'El elemento :attribute ya ha sido registrado.',
    'uploaded' => 'El elemento :attribute fallo en ser cargado.',
    'url' => 'El formato del elemento :attribute es invalido.',
    'uuid' => 'El elemento :attribute debe ser un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
