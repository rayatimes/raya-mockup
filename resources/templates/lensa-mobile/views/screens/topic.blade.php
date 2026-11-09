@php
    $isCategory = $kind === 'category';
    $scope = ($isCategory ? 'category:' : 'tag:').$topic['slug'];
    $list = $rt->scope($scope);
    $children = $isCategory ? $rt->childCategories($topic['slug']) : [];
    $cover = $list[0] ?? null;
@endphp
<div class="scr" data-sb="light" data-solid="false" data-solid-at="170">
    <header class="appbar appbar-topic">
        <button type="button" class="icon-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
        <h1 class="appbar-title">{{ $topic['name'] }}</h1>
    </header>
    <div class="scroll" data-scroll>
        <div class="topic-hero" data-theme="dark">
            @if ($cover)<img src="{{ $cover['image']['lg'] }}" alt="" fetchpriority="high">@endif
            <div class="topic-hero-text">
                <p class="hero-label">{{ $isCategory ? 'Kanal' : 'Topik' }} · {{ count($list) }} berita</p>
                <h2 class="topic-title">{{ $isCategory ? $topic['name'] : '#'.$topic['name'] }}</h2>
                @if (! empty($topic['description']))<p class="topic-desc">{{ $topic['description'] }}</p>@endif
            </div>
        </div>
        @if ($children)
            <div class="chips chips-pad" data-drag>
                @foreach ($children as $child)
                    <a href="{{ $child['url'] }}" class="m-chip">{{ $child['name'] }}</a>
                @endforeach
            </div>
        @endif
        @if ($list)
            <div class="feed-gap" data-feed data-scope="{{ $scope }}" data-offset="10" data-per="10">
                @include('lensa-mobile::partials.rows', ['items' => array_slice($list, 0, 10)])
            </div>
            @if (count($list) > 10)<div class="sentinel" data-sentinel><span class="m-spinner"></span></div>@endif
        @else
            <x-lensa-mobile::empty title="Belum Ada Berita" message="Belum ada berita yang terbit di sini." />
        @endif
        <div class="article-end"></div>
    </div>
</div>
