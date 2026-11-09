@php
    $isCategory = $kind === 'category';
    $scope = ($isCategory ? 'category:' : 'tag:').$topic['slug'];
    $list = $rt->scope($scope);
    $children = $isCategory ? $rt->childCategories($topic['slug']) : [];
@endphp
<div class="scr" data-sb="auto" data-kanal="{{ $topic['slug'] }}">
    <x-default-mobile::appbar :title="$topic['name']" />
    <div class="scroll" data-scroll>
        <div class="topic-head">
            <p class="row-label">{{ $isCategory ? 'Kanal' : 'Tag' }}</p>
            <h2 class="topic-title">{{ $topic['name'] }}</h2>
            @if (! empty($topic['description']))<p class="topic-desc">{{ $topic['description'] }}</p>@endif
            <p class="row-meta">{{ count($list) }} berita</p>
            @if ($children)
                <div class="chips" data-drag>
                    @foreach ($children as $child)
                        <a href="{{ $child['url'] }}" class="m-chip">{{ $child['name'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        @if ($list)
            <div data-feed data-scope="{{ $scope }}" data-offset="10" data-per="10">
                @include('default-mobile::partials.rows', ['items' => array_slice($list, 0, 10)])
            </div>
            @if (count($list) > 10)<div class="sentinel" data-sentinel><span class="m-spinner"></span></div>@endif
        @else
            <x-default-mobile::empty title="Belum Ada Berita" message="Belum ada berita yang terbit di sini." />
        @endif
        <div class="article-end"></div>
    </div>
</div>
