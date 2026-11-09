@php
    $notifications = $rt->mobileNotifications();
    $filters = ['' => 'Semua', 'breaking' => 'Berita Penting', 'kanal' => 'Dari Minat Anda', 'sistem' => 'Info Akun'];
@endphp
<div class="scr" data-sb="auto">
    <x-lensa-mobile::appbar title="Notifikasi" :back="false"><button type="button" class="section-more" data-notif-clear>Hapus Semua</button></x-lensa-mobile::appbar>
    <div class="scroll" data-scroll>
        <div class="chips chips-pad" data-notif-filters data-drag>
            @foreach ($filters as $type => $label)
                <button type="button" class="m-chip" data-nfilter="{{ $type }}" aria-pressed="{{ $type === '' ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div data-notif-list>
            @foreach ($notifications as $notification)
                <a href="{{ $notification['url'] }}" class="notif" data-notif="{{ $notification['id'] }}" data-type="{{ $notification['type'] }}" data-read="false">
                    <span class="notif-dot"></span>
                    <span class="notif-text">
                        <span class="notif-type">{{ $notification['label'] }}</span>
                        <span class="notif-title">{{ $notification['title'] }}</span>
                        @if ($notification['body'])<span class="notif-body">{{ $notification['body'] }}</span>@endif
                        <span class="row-meta">{{ $notification['ago'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
        <x-lensa-mobile::empty data-notif-empty hidden icon="ri-notification-3-line" title="Belum Ada Notifikasi" message="Pemberitahuan berita terbaru dan info akun akan tampil di sini." />
        <div class="page-end"></div>
    </div>
</div>
