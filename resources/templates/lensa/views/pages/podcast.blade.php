@extends('lensa::layouts.app', ['title' => 'Siniar'])

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <section class="relative overflow-hidden rounded-frame bg-panel p-8 lg:p-16">
            <div class="flex h-24 items-end gap-1.5" aria-hidden="true">
                @foreach ([20, 45, 70, 35, 90, 55, 75, 30, 60, 85, 40, 65, 25, 50, 80, 35, 55, 70, 45, 30] as $i => $h)
                    <span class="w-2 rounded-full bg-sun" style="height: {{ $h }}%; opacity: {{ 0.35 + ($i % 5) * 0.13 }}"></span>
                @endforeach
            </div>
            <h1 class="ls-headline mt-8 text-6xl lg:text-8xl">Siniar</h1>
            <p class="mt-3 max-w-xl text-lg text-ink/80">Dengarkan cerita di balik berita: obrolan, wawancara, dan diskusi mendalam bersama narasumber.</p>
            <p class="mt-8 text-dim">Episode perdana sedang disiapkan.</p>
            <button type="button" data-toggle-press aria-pressed="false" class="ls-btn mt-4 aria-[pressed=true]:bg-ink"><i class="ri-notification-3-line"></i>Ingatkan saya saat tayang</button>
        </section>
    </div>
@endsection
