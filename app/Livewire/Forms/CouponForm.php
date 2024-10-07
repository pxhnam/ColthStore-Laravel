<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Enums\CouponType;
use Livewire\Attributes\Validate;

class CouponForm extends Form
{
    public $id;
    public $code;
    public $value;
    public $type;
    public $min = 0;
    public $max;
    public $count = 0;
    public $limit;
    public $desc;
    public $start;
    public $expiry;

    #[Validate]
    protected function rules(): array
    {
        return [
            'code' =>
            [
                'required',
                'string',
                'regex:/^[A-Za-z0-9]+$/',
                $this->id ? 'unique:coupons,code,' . $this->id : 'unique:coupons,code'
            ],
            'value' => 'required|integer|min:0',
            'type' => 'required|in:' . implode(',', CouponType::getValues()),
            'min' => 'required|integer|min:0',
            'max' => 'nullable|integer|gte:min',
            'limit' => 'nullable|integer|min:0|gte:count',
            'desc' => 'required|string',
            'start' => 'required|date_format:Y-m-d\TH:i|before_or_equal:expiry',
            'expiry' => 'nullable|date_format:Y-m-d\TH:i|after:start',
        ];
    }
}
