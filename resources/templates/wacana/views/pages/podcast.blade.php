@extends('wacana::layouts.app', ['title' => 'Siniar'])

@section('content')
    <div class="mx-auto mt-12 max-w-[84rem] px-4 lg:px-8">
        <div class="grid gap-10 bg-forest p-8 text-paper lg:grid-cols-2 lg:p-16">
            <div>
                <p class="text-sm font-semibold text-beige">Siniar RayaTimes</p>
                <h1 class="wc-display mt-3 text-6xl lg:text-7xl">Dengarkan cerita di balik berita</h1>
            </div>
            <div class="self-end">
                <p class="font-serif text-xl leading-relaxed text-paper/85">Obrolan, wawancara, dan diskusi mendalam bersama narasumber. Episode perdana sedang disiapkan oleh redaksi.</p>
                <button type="button" data-press aria-pressed="false" class="mt-6 bg-paper px-5 py-3 text-sm font-semibold text-forest transition hover:bg-beige aria-[pressed=true]:bg-sun">Kabari saya saat tayang</button>
            </div>
        </div>
    </div>
@endsection
