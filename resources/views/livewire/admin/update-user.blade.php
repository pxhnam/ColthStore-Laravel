<div class="row table-responsive shadow-sm bg-body-tertiary rounded">
    <a class="btn-back" href="{{ route('admin.users.index') }}" wire:navigate>
        <i class='bx bxs-chevron-left'></i>
        Back
    </a>
    <form action="" autocomplete="off" class="row p-3" wire:submit.prevent='submit'>
        <div class="col-md-12 text-center text-uppercase mb-3">
            <h3 class="fw-bold">CREATE USER</h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-floating">
                <input type="text"
                    @if ($errors->has('user.name')) class='form-control is-invalid' @else class='form-control' @endif
                    name="name" id="name" placeholder="" wire:model='user.name'>
                <label for="name">Name</label>
            </div>
            @error('user.name')
                <span class="text-danger fst-italic">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-floating">
                <input type="text"
                    @if ($errors->has('user.email')) class='form-control is-invalid' @else class='form-control' @endif
                    name="email" id="email" placeholder="" wire:model='user.email'>
                <label for="email">Email</label>
            </div>
            @error('user.email')
                <span class="text-danger fst-italic">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-floating">
                <input type="password"
                    @if ($errors->has('user.password')) class='form-control is-invalid' @else class='form-control' @endif
                    name="password" id="password" placeholder="" wire:model='user.password'>
                <label for="password">Password</label>
            </div>
            @error('user.password')
                <span class="text-danger fst-italic">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-floating">
                <select class="form-select" id="floatingSelect" aria-label="State user" wire:model='user.state'>
                    @foreach ($states as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">State</label>
            </div>
        </div>
        <div class="col-md-8 mb-3 d-flex align-items-center gap-2">
            <label for="">Role: </label>
            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                @foreach ($roles as $role)
                    <input type="checkbox" class="btn-check" id="{{ $role }}" value="{{ $role }}"
                        wire:model='user.roles'>
                    <label class="btn btn-outline-primary" for="{{ $role }}">{{ $role }}</label>
                @endforeach
            </div>
            @error('user.roles')
                <span class="text-danger fst-italic">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </div>
    </form>
</div>
@script
    <script>
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
