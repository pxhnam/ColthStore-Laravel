<?php

namespace App\Livewire\Admin;

use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Livewire\Components\AdminComponent;
use App\Models\Coupon;

class TableCoupon extends AdminComponent
{

    public function create()
    {
        $this->dispatch('create-coupon');
    }

    public function update($id)
    {
        $this->dispatch('update-coupon', id: $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-coupons')]
    public function render()
    {
        $coupons = Coupon::query()
            ->where(function ($query) {
                $query->where('code', 'like', "%{$this->search}%");
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-coupon',
            [
                'coupons' => $coupons
            ]
        );
    }
}
