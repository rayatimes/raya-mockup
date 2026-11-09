{{-- Lembar bawah: ukuran teks, bagikan, kelola artikel, dan konfirmasi. --}}
<div class="sheet" data-sheet="text" data-open="false" role="dialog" aria-label="Ukuran teks">
    <span class="sheet-grip"></span>
    <h2 class="sheet-title">Ukuran Teks</h2>
    <div class="sheet-options" role="radiogroup">
        @foreach (['kecil' => ['Kecil', '90% dari ukuran normal'], 'normal' => ['Normal', 'Ukuran bawaan aplikasi'], 'besar' => ['Besar', '115% dari ukuran normal'], 'sangat-besar' => ['Sangat Besar', '130% dari ukuran normal']] as $value => [$label, $note])
            <button type="button" class="sheet-option" role="radio" data-text-option="{{ $value }}" aria-checked="false">
                <span><b>{{ $label }}</b><small>{{ $note }}</small></span>
                <i class="ri-check-line"></i>
            </button>
        @endforeach
    </div>
    <p class="sheet-preview">Fakta, makna, dan daya bangsa: contoh ukuran teks saat membaca berita.</p>
    <button type="button" class="m-btn m-btn-solid" data-overlay-close>Selesai</button>
</div>

<div class="sheet" data-sheet="share" data-open="false" role="dialog" aria-label="Bagikan">
    <span class="sheet-grip"></span>
    <h2 class="sheet-title">Bagikan</h2>
    <p class="sheet-note" data-share-title></p>
    <div class="share-grid">
        @foreach (['whatsapp' => ['ri-whatsapp-line', 'WhatsApp'], 'telegram' => ['ri-telegram-line', 'Telegram'], 'x' => ['ri-twitter-x-line', 'X'], 'facebook' => ['ri-facebook-fill', 'Facebook']] as $network => [$icon, $label])
            <a href="#" target="_blank" rel="noopener" data-share="{{ $network }}" class="share-item"><span><i class="{{ $icon }}"></i></span>{{ $label }}</a>
        @endforeach
    </div>
    <button type="button" class="sheet-row" data-share-copy><i class="ri-link"></i>Salin Tautan</button>
    <button type="button" class="sheet-row" data-share-native><i class="ri-share-forward-line"></i>Lainnya</button>
</div>

<div class="sheet" data-sheet="more" data-open="false" role="dialog" aria-label="Kelola artikel">
    <span class="sheet-grip"></span>
    <p class="sheet-article" data-more-title></p>
    <button type="button" class="sheet-row" data-save=""><i class="ri-bookmark-line"></i><span data-more-save-label>Simpan Berita</span></button>
    <button type="button" class="sheet-row" data-more-share><i class="ri-share-forward-line"></i>Bagikan</button>
    <a href="#" class="sheet-row" data-more-cat><i class="ri-price-tag-3-line"></i><span></span></a>
    <a href="#" class="sheet-row" data-more-author><i class="ri-user-3-line"></i><span></span></a>
</div>

<div class="sheet" data-sheet="voice" data-open="false" role="dialog" aria-label="Suara pembaca">
    <span class="sheet-grip"></span>
    <h2 class="sheet-title">Suara Pembaca</h2>
    <p class="sheet-note">Suara berbahasa Indonesia yang terpasang di perangkat ini.</p>
    <div class="voice-list" data-voice-list role="radiogroup"></div>
</div>

<div class="sheet" data-sheet="confirm" data-open="false" role="alertdialog">
    <span class="sheet-grip"></span>
    <h2 class="sheet-title" data-confirm-title></h2>
    <p class="sheet-note" data-confirm-message></p>
    <div class="sheet-actions">
        <button type="button" class="m-btn m-btn-line" data-overlay-close>Batal</button>
        <button type="button" class="m-btn m-btn-solid" data-confirm-ok></button>
    </div>
</div>
