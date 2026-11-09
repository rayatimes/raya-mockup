@php
    $pages = array_values(array_filter($rt->pages(), fn ($page) => in_array($page['slug'], ['about-us', 'contact-us', 'privacy-policy'], true)));
@endphp
<div class="scr" data-sb="auto">
    <x-wacana-mobile::appbar title="Profil" :back="false" />
    <div class="scroll" data-scroll>
        <div class="person">
            <x-wacana-mobile::avatar :person="$member" :size="84" />
            <h2 class="person-name">{{ $member['name'] }}</h2>
            <p class="person-role">{{ '@'.$member['username'] }}</p>
            <p class="person-bio">{{ $member['bio'] }}</p>
            <div class="stats">
                <div><b data-stat="read">0</b><span>Dibaca</span></div>
                <div><b data-stat="saved">0</b><span>Tersimpan</span></div>
                <div><b data-stat="interests">0</b><span>Minat</span></div>
            </div>
            <a href="{{ $rt->url('/profil/edit') }}" class="m-btn m-btn-soft">Edit Profil</a>
        </div>

        <section class="group">
            <h3 class="group-title">Bacaan Saya</h3>
            <a href="{{ $rt->url('/tersimpan') }}" class="set-row"><i class="ri-bookmark-line"></i><span>Tersimpan</span><i class="ri-arrow-right-s-line"></i></a>
            <a href="{{ $rt->url('/riwayat') }}" class="set-row"><i class="ri-time-line"></i><span>Riwayat Membaca</span><i class="ri-arrow-right-s-line"></i></a>
            <a href="{{ $rt->url('/minat') }}" class="set-row"><i class="ri-price-tag-3-line"></i><span>Minat Topik<small>Mengatur isi tab Untuk Anda</small></span><i class="ri-arrow-right-s-line"></i></a>
        </section>

        <section class="group">
            <h3 class="group-title">Tampilan</h3>
            <div class="set-row"><i class="ri-moon-line"></i><span>Mode Gelap</span><button type="button" class="switch" role="switch" data-switch="theme" aria-checked="false" aria-label="Mode gelap"></button></div>
            <button type="button" class="set-row" data-sheet-open="text"><i class="ri-font-size"></i><span>Ukuran Teks</span><em data-text-value>Normal</em><i class="ri-arrow-right-s-line"></i></button>
        </section>

        <section class="group">
            <h3 class="group-title">Pemberitahuan</h3>
            <div class="set-row"><i class="ri-notification-3-line"></i><span>Notifikasi Dorong<small>Kabar penting saat aplikasi ditutup</small></span><button type="button" class="switch" role="switch" data-switch="push" aria-checked="true" aria-label="Notifikasi dorong"></button></div>
            <div class="set-row"><i class="ri-flashlight-line"></i><span>Berita Penting</span><button type="button" class="switch" role="switch" data-switch="breaking" aria-checked="true" aria-label="Berita penting"></button></div>
            <div class="set-row"><i class="ri-heart-3-line"></i><span>Dari Minat Anda</span><button type="button" class="switch" role="switch" data-switch="interest" aria-checked="true" aria-label="Dari minat Anda"></button></div>
        </section>

        <section class="group">
            <h3 class="group-title">Bantuan &amp; Legal</h3>
            @foreach ($pages as $page)
                <a href="{{ $rt->url('/p/'.$page['slug']) }}" class="set-row"><i class="ri-file-text-line"></i><span>{{ $page['title'] }}</span><i class="ri-arrow-right-s-line"></i></a>
            @endforeach
        </section>

        <div class="profile-end">
            <button type="button" class="m-btn m-btn-line" data-logout>Keluar</button>
            <p>RayaTimes versi 1.1.5</p>
        </div>
        <div class="page-end"></div>
    </div>
</div>
