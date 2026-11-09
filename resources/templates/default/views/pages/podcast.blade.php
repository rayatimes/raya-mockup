@extends('default::layouts.app', ['title' => 'Siniar'])

@section('content')
    <div class="mx-auto max-w-7xl px-4 pt-6">
        <header class="bg-raya bg-[linear-gradient(120deg,var(--color-raya)_55%,var(--color-raya-deep))] p-6 text-white lg:p-10">
            <p class="flex items-center gap-1.5 text-xs font-bold text-sun"><i class="ri-mic-line text-sm"></i>Siniar RayaTimes</p>
            <h1 class="mt-2 font-serif text-4xl font-semibold">Dengarkan cerita di balik berita</h1>
            <p class="mt-2 text-white/80">Obrolan, wawancara, dan diskusi mendalam bersama para narasumber.</p>
        </header>
        <div class="mx-auto mt-12 max-w-lg text-center">
            <div class="mx-auto flex h-16 w-40 items-end justify-center gap-1" aria-hidden="true">
                @foreach ([30, 60, 45, 80, 50, 70, 35, 55, 25] as $h)<span class="w-2 bg-line" style="height: {{ $h }}%"></span>@endforeach
            </div>
            <h2 class="mt-6 font-serif text-2xl font-semibold">Episode perdana sedang disiapkan</h2>
            <p class="mt-2 text-sm text-muted">Ikuti kanal Siniar agar Anda mendapat notifikasi saat episode pertama tayang.</p>
            <button type="button" data-react data-on="ri-notification-3-fill" data-off="ri-notification-3-line" aria-pressed="false" class="rt-btn rt-btn-solid mt-6"><i class="ri-notification-3-line"></i>Ingatkan saya</button>
        </div>
    </div>
@endsection
