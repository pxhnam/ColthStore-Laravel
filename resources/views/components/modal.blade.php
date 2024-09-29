@props([
    'title' => '',
    'center' => false,
    'size' => '',
    'scroll' => false,
    'static' => false,
    'btnClose' => '',
    'btnDone' => '',
    'submit' => false,
])
@php
    $classes = 'modal-dialog';
    if ($center) {
        $classes .= ' modal-dialog-centered';
    }
    if ($size == 'small') {
        $classes .= ' modal-sm';
    } elseif ($size == 'large') {
        $classes .= ' modal-lg';
    } elseif ($size == 'extra-large') {
        $classes .= ' modal-xl';
    }
    if ($scroll) {
        $classes .= ' modal-dialog-scrollable';
    }
    $btn = 'button';
    if ($submit) {
        $btn = 'submit';
    }
@endphp
<div wire:ignore.self class="modal fade" data-bs-keyboard="false" tabindex="-1"
    @if ($static) data-bs-backdrop="static" @endif>
    <div class="{{ $classes }}">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    wire:click='close()'></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if ($btnClose || $btnDone)
                <div class="modal-footer">
                    @if ($btnClose)
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click='close()'>
                            {{ $btnClose }}
                        </button>
                    @endif
                    @if ($btnDone)
                        <button type="{{ $btn }}" class="btn btn-primary">
                            {{ $btnDone }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
