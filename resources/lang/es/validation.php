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

    'accepted'             => 'El elemento :attribute debe ser aceptado.',
    'active_url'           => 'El elemento :attribute no es una URL valida.',
    'after'                => 'El elemento :attribute debe ser una fecha después de :date.',
    'after_or_equal'       => 'El elemento :attribute debe ser una fecha después o igual a :date.',
    'alpha'                => 'El elemento :attribute solo debe contener letras.',
    'alpha_dash'           => 'El elemento :attribute solo debe contener letras, números, guiones y guion bajo.',
    'alpha_num'            => 'El elemento :attribute solo debe contener letras y números.',
    'array'                => 'El elemento :attribute Debe ser un array.',
    'before'               => 'El elemento :attribute debe ser una fecha antes de :date.',
    'before_or_equal'      => 'El elemento :attribute debe ser una fecha antes o igual a :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'La verificación de :attribute no coincide.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'El elemento :attribute no coincide con el formato de fecha dd/mm/aaaa.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'El elemento :attribute debe ser un archivo.',
    'filled'               => 'The :attribute field must have a value.',
    'gt'                   => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image'                => 'El elemento :attribute debe ser una imagen.',
    'in'                   => 'El elemento :attribute seleccionado es invalido.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'lt'                   => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => 'El elemento :attribute no debe ser mayor a :max.',
        'file'    => 'El elemento :attribute no debe ser mayor a :max kilobytes.',
        'string'  => 'El elemento :attribute no debe ser mayor a :max caaracteres.',
        'array'   => 'El elemento :attribute no debe tener más de :max items.',
    ],
    'mimes'                => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El elemento :attribute debe ser al menos :min.',
        'file'    => 'El elemento :attribute debe ser de al menos :min kilobytes.',
        'string'  => 'El elemento :attribute debe ser de al menos :min characteres.',
        'array'   => 'El elemento :attribute debe contener al menos :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => 'El elemento :attribute debe ser un número.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'El elemento :attribute es requerido.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'El elemento :attribute es requerido cuando el elemento :values esta presente.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'El elemento :attribute y :other deben ser iguales.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'El elemento :attribute ya ha sido registrado.',
    'uploaded'             => 'El elemento :attribute no se ha podido cargar.',
    'url'                  => 'The :attribute format is invalid.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
