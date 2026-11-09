@php
    $isCategory = $kind === 'category';
    $scope = ($isCategory ? 'category:' : 'tag:').$topic['slug'];
    $list = $rt->scope($scope);
    $children = $isCategory ? $rt->childCategories($topic['slug']) : [];
@endphp
<div class="scr" data-sb="light" data-kanal="{{ $topic['slug'] }}">
    <header class="appbar appbar-field">
        <button type="button" class="icon-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
        <span class="appbar-title">{{ $isCategory ? 'Kanal' : 'Topik' }}</span>
    </header>
    <div class="scroll" data-scroll>
        <div class="topic-field">
            <h1 class="topic-title">{{ $topic['name'] }}</h1>
            @if (! empty($topic['description']))<p class="topic-desc">{{ $topic['description'] }}</p>@endif
            <p class="topic-count">{{ count($list) }} berita</p>
        </div>
        @if ($children)
            <div class="chips chips-pad" data-drag>
                @foreach ($children as $child)
                    <a href="{{ $child['url'] }}" class="m-chip">{{ $child['name'] }}</a>
                @endforeach
            </div>
        @endif
        @if ($list)
            <div data-feed data-scope="{{ $scope }}" data-offset="10" data-per="10">
                @include('wacana-mobile::partials.rows', ['items' => array_slice($list, 0, 10), 'showCategory' => ! $isCategory || $children])
            </div>
            @if (count($list) > 10)<div class="sentinel" data-sentinel><span class="m-spinner"></span></div>@endif
        @else
            <x-wacana-mobile::empty title="Belum Ada Berita" message="Belum ada berita yang terbit di sini." />
        @endif
        <div class="article-end"></div>
    </div>
</div>
