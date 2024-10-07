<?php

namespace App\Livewire\Admin;

use App\Enums\CouponType;
use Exception;
use Carbon\Carbon;
use App\Models\Coupon;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\CouponForm;
use Illuminate\Support\Facades\Log;

class UpdateCoupon extends Component
{
    public $title = '';
    public CouponForm $form;

    #[On('create-coupon')]
    public function create()
    {
        $this->title = 'New Coupon';
        $this->dispatch('open-modal');
    }

    #[On('update-coupon')]
    public function update($id)
    {
        if ($id) {
            try {
                $this->form->id = $id;
                $coupon = Coupon::findOrFail($id);
                $this->form->code = $coupon->code;
                $this->form->value = $coupon->value;
                $this->form->type = $coupon->type;
                $this->form->min = $coupon->min;
                $this->form->max = $coupon->max;
                $this->form->count = $coupon->count;
                $this->form->limit = $coupon->limit;
                $this->form->start = Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i');
                $this->form->expiry =
                    $coupon->expiry_date ?
                    Carbon::parse($coupon->expiry_date)
                    ->format('Y-m-d\TH:i') : null;
                $this->form->desc = $coupon->desc;
                $this->form->type = $coupon->type;
                $this->title = 'Edit Coupon';
                $this->dispatch('open-modal');
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
            }
        }
    }

    public function submit()
    {
        $this->form->validate();
        try {
            if ($this->form->type === CouponType::FIXED->value) {
                $this->form->max = null;
            }
            if ($this->form->id) {
                $coupon = Coupon::findOrFail($this->form->id);
                $coupon->code = $this->form->code;
                $coupon->value = $this->form->value;
                $coupon->type = $this->form->type;
                $coupon->min = $this->form->min;
                $coupon->max = $this->form->max;
                $coupon->limit = $this->form->limit;
                $coupon->start_date = $this->form->start;
                $coupon->expiry_date = $this->form->expiry;
                $coupon->desc = $this->form->desc;
                $coupon->type = $this->form->type;
                $coupon->save();
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                Coupon::create([
                    'code' => strtoupper($this->form->code),
                    'value' => $this->form->value,
                    'type' => $this->form->type,
                    'min' => $this->form->min,
                    'max' => $this->form->max,
                    'limit' => $this->form->limit,
                    'desc' => $this->form->desc,
                    'start_date' => $this->form->start,
                    'expiry_date' => $this->form->expiry
                ]);
                $this->form->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-coupons');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function close()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.admin.update-coupon');
    }
}
