@php
    use App\Enums\CouponType;
@endphp
<form wire:submit.prevent='submit' autocomplete="off">
    <x-modal title='{{ $title }}' size='large' center=true static=true btnClose='Close' btnDone='Save' submit=true>
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type='text' id='Code' placeholder='Code' wire:model='form.code'
                            @if ($errors->has('form.code')) class='form-control is-invalid'
                            @else class='form-control' @endif
                            style='text-transform: uppercase;' />
                        <label for='Code'>Code</label>
                    </div>
                    @error('form.code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type='number' id='value' placeholder='Value' wire:model='form.value'
                            @if ($errors->has('form.value')) class='form-control is-invalid'
                            @else class='form-control' @endif />
                        <label for='value'>Value</label>
                    </div>
                    @switch($form->type)
                        @case(CouponType::FIXED->value)
                            <span class="input-group-text">VNĐ</span>
                        @break

                        @case(CouponType::PERCENT->value)
                            <span class="input-group-text">%</span>
                        @break

                        @default
                    @endswitch
                    @error('form.value')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type='number' id='Min' placeholder='Min' wire:model='form.min'
                            @if ($errors->has('form.min')) class='form-control is-invalid'
                            @else class='form-control' @endif />
                        <label for='Min'>Min</label>
                    </div>
                    @error('form.min')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type='number' id='Max' placeholder='Max' wire:model='form.max'
                            @if ($errors->has('form.max')) class='form-control is-invalid'
                            @else class='form-control' @endif
                            @disabled($form->type === CouponType::FIXED->value) />
                        <label for='Max'>Max</label>
                    </div>
                    @error('form.max')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-floating">
                    <input type='number' class='form-control' name='count' id='count' placeholder=''
                        wire:model='form.count' @disabled(true) />
                    <label for='count'>Count</label>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type='number' id='Limit' placeholder='Limit' wire:model='form.limit'
                            @if ($errors->has('form.limit')) class='form-control is-invalid'
                            @else class='form-control' @endif />
                        <label for='Limit'>Limit</label>
                    </div>
                    @error('form.limit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type="datetime-local" id="start-date" name="start-date"
                            @if ($errors->has('form.start')) class='form-control is-invalid'
                            @else class='form-control' @endif
                            wire:model='form.start' />
                        <label for="start-date">Start Date</label>
                    </div>
                    @error('form.start')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <input type="datetime-local" id="expiry-date" name="expiry-date"
                            @if ($errors->has('form.expiry')) class='form-control is-invalid'
                            @else class='form-control' @endif
                            wire:model='form.expiry' />
                        <label for="expiry-date">Expiry Date</label>
                    </div>
                    @error('form.expiry')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="input-group has-validation">
                    <div class="form-floating is-invalid">
                        <textarea placeholder='' id='description' style='height: 120px'
                            @if ($errors->has('form.desc')) class='form-control is-invalid'
                            @else class='form-control' @endif
                            wire:model='form.desc'></textarea>
                        <label for='description'>Description</label>
                    </div>
                    @error('form.desc')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="input-group has-validation checkbox-group">
                    <span>Type: </span>
                    <div class="btn-group is-invalid" role="group" aria-label=''>
                        @foreach (CouponType::cases() as $type)
                            <input type="radio" class="btn-check" name="btnradio" id="{{ $type }}"
                                autocomplete='off' wire:model.live='form.type' value="{{ $type }}" />
                            <label class="btn btn-outline-primary"
                                for="{{ $type }}">{{ $type }}</label>
                        @endforeach
                    </div>
                    @error('form.type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </x-modal>
</form>
@script
    <script>
        $wire.on('open-modal', () => {
            $('.modal').modal('show');
        });
        $wire.on('close-modal', () => {
            $('.modal').modal('hide');
        });
        $wire.on('success', msg => {
            Swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            });
        });
        $wire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            });
        });
    </script>
@endscript
