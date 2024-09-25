<?php

return [
    'required' => ':attribute不能为空值。',
    'size' => [
            'string' => ':attribute长度只能为:size位',
    ],
    'max' => [
        // 'numeric' => 'The :attribute may not be greater than :max.',
        // 'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => ':attribute不能超过:max位.',
        // 'array'   => 'The :attribute may not have more than :max items.',
    ],
    'min' => [
        'string'  => ':attribute不能小于:min位.',
    ],
    'attributes' => [
        'mobile'    => '手机号码',
        'vin'       => '车架号(VIN码)',
        'plate_no'  => '车牌号',
    ],
];
