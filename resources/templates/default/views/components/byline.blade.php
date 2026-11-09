@props(['article', 'rt'])
<p {{ $attributes->merge(['class' => 'rt-meta flex items-center gap-2']) }}>
    @if ($article['author'])
        <x-default::avatar :author="$article['author']" />
        <a href="{{ $article['author']['url'] }}" class="font-medium text-fg hover:text-accent">{{ $article['author']['name'] }}</a>@if (count($article['authors']) > 1)<span>dan {{ count($article['authors']) - 1 }} lainnya</span>@endif
        <span aria-hidden="true">·</span>
    @endif
    <time datetime="{{ $article['published']->toIso8601String() }}">{{ $rt->ago($article['published']) }}</time>
</p>
