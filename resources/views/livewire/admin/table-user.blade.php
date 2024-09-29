@php
    use App\Enums\UserState;
@endphp
<div class="row table-responsive shadow-sm p-3 bg-body-tertiary rounded">
    {{-- <div class="col-md-12"> --}}
    {{-- <div class="row"> --}}
    <div class="col-12 col-md-1">
        <div class="form-floating">
            <input type="number" class='form-control' name="page-size" id="page-size" placeholder=""
                wire:model.live='pageSize'>
            <label for="page-size">Page size</label>
        </div>
    </div>
    <div class="col-12 col-md-1">
        <div class="form-floating">
            <select class="form-select" id="role" aria-label="Role user" wire:model.change='role'>
                <option value="">ALL</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <label for="role">Role</label>
        </div>
    </div>
    <div class="col-12 col-md-1">
        <div class="form-floating">
            <select class="form-select" id="state" aria-label="State user" wire:model.change='state'>
                <option value="">ALL</option>
                @foreach (UserState::cases() as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
            </select>
            <label for="state">State</label>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="form-floating">
            <input type="text" class='form-control' name="search" id="search" placeholder=""
                wire:model.live='search' autocomplete="off">
            <label for="search">Search...</label>
        </div>
    </div>
    <div class="col-12 col-md-5"></div>
    <div class="col-12 col-md-1 d-flex justify-content-end align-items-center">
        <button class="btn btn-primary text-end" wire:click='create()'>
            <i class='bx bxs-user-plus'></i>
        </button>
    </div>
    {{-- </div> --}}
    <table class="table table-bordered table-hover text-center mt-2">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" wire:click='sort("Name")'>
                    Name
                    @if ($sortBy == 'Name')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("Email")'>
                    Email
                    @if ($sortBy == 'Email')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">Role</th>
                <th scope="col">State</th>
                <th scope="col" wire:click='sort("email_verified_at")'>
                    Email Verified At
                    @if ($sortBy == 'email_verified_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("updated_at")'>
                    Updated At
                    @if ($sortBy == 'updated_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("created_at")'>
                    Created At
                    @if ($sortBy == 'created_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <div class="col-12">
            <tbody wire:loading.remove wire:target='users'>
                @forelse ($users ?? [] as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->hasRole('USER'))
                                <span class="badge text-bg-secondary">USER</span>
                            @endif
                            @if ($user->hasRole('ADMIN'))
                                <span class="badge text-bg-primary">ADMIN</span>
                            @endif

                        </td>
                        <td>
                            @php
                                $badgeClass = match ($user->state) {
                                    UserState::PENDING->value => 'text-bg-secondary',
                                    UserState::ACTIVED->value => 'text-bg-primary',
                                    UserState::DISABLED->value => 'text-bg-warning',
                                    UserState::REMOVED->value => 'text-bg-danger',
                                    default => 'text-bg-light',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $user->state }}</span>
                        </td>
                        <td>{{ $user->email_verified_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a class="btn btn-success" href=" {{ route('admin.users.edit', ['id' => $user->id]) }}"
                                wire:navigate>
                                <i class='bx bxs-edit-alt'></i>
                            </a>
                            <button class="btn btn-danger" wire:click='destroy("{{ $user->id }}")'>
                                <i class='bx bxs-trash-alt'></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='12'>TABLE EMPTY</td>
                    </tr>
                @endforelse
            </tbody>
        </div>
    </table>
    <p class="text-center" wire:loading wire:target='users'>LOADING...</p>
    {{ $users->links() }}
    {{-- </div> --}}
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
    </script>
@endscript
