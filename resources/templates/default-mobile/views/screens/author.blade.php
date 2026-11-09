@php
    $scope = 'author:'.$author['username'];
    $list = $rt->scope($scope);
@endphp
<div class="scr" data-sb="auto">
    <x-default-mobile::appbar :title="$author['name']" />
    <div class="scroll" data-scroll>
        <div class="person">
            <x-default-mobile::avatar :person="$author" :size="72" />
            <h2 class="person-name">{{ $author['name'] }}</h2>
            <p class="person-role">{{ $author['label'] ?: 'Penulis' }} • {{ count($list) }} tulisan</p>
            @if ($author['bio'])<p class="person-bio">{{ $author['bio'] }}</p>@endif
        </div>
        <h2 class="section-title section-title-pad">Tulisan</h2>
        @if ($list)
            <div data-feed data-scope="{{ $scope }}" data-offset="10" data-per="10">
                @include('default-mobile::partials.rows', ['items' => array_slice($list, 0, 10)])
            </div>
            @if (count($list) > 10)<div class="sentinel" data-sentinel><span class="m-spinner"></span></div>@endif
        @else
            <x-default-mobile::empty title="Belum Ada Tulisan" message="Penulis ini belum menerbitkan tulisan." />
        @endif
        <div class="article-end"></div>
    </div>
</div>
