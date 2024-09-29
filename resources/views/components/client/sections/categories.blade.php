<div class="sec-banner bg0 p-t-80 p-b-50">
    <div class="container">
        <div class="row">
            @foreach ($categories ?? [] as $category)
                <x-client.blocks.category name='{{ $category->name }}' sub='{{ $category->desc }}'
                    path='storage/{{ $category->pic }}' />
            @endforeach
        </div>
    </div>
</div>
