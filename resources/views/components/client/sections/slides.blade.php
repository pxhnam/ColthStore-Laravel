<section class="section-slide">
    <div class="wrap-slick1">
        <div class="slick1">
            @foreach ($banners ?? [] as $index => $banner)
                @php
                    if (!isset($i) || $i > 2) {
                        $i = 0;
                    }
                @endphp
                <x-client.blocks.banner title='{{ $banner->title }}' sub='{{ $banner->sub }}'
                    path='storage/{{ $banner->path }}' effectTitle='{{ $effects[$i][$i] }}'
                    effectSub='{{ $effects[$i][$i] }}' effectButton='{{ $effects[$i][$i] }}' />
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</section>
