@props([
    'id' => null,
    'name' => 'Unknow',
    'type' => '',
    'pic' => '',
    'link' => 'javascript:void(0)',
    'cost' => '$0',
])
<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $type }}">
    <!-- Block2 -->
    <div class="block2">
        <div class="block2-pic hov-img0">
            {{-- <img src="{{ asset('assets/client/images/' . $pic) }}" alt="{{$name}}" /> --}}
            <img src="{{ asset($pic) }}" alt="{{ $name }}"
                onerror="this.onerror=null; this.src='{{ asset('storage/uploads/Image-Not-Found.jpg') }}';" />

            <a href="#" data-id='{{ $id }}'
                class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                Quick View
            </a>
        </div>

        <div class="block2-txt flex-w flex-t p-t-14">
            <div class="block2-txt-child1 flex-col-l ">
                <a href="{{ $link }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                    {{ $name }}
                </a>

                <span class="stext-105 cl3">
                    {{ $cost }}
                </span>
            </div>

            <div class="block2-txt-child2 flex-r p-t-3">
                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                    <img class="icon-heart1 dis-block trans-04"
                        src="{{ asset('assets/client/images/icons/icon-heart-01.png') }}" alt="ICON">
                    <img class="icon-heart2 dis-block trans-04 ab-t-l"
                        src="{{ asset('assets/client/images/icons/icon-heart-02.png') }}" alt="ICON">
                </a>
            </div>
        </div>
    </div>
</div>
