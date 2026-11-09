<div {{ $attributes->merge(['class' => 'flex items-center gap-1']) }} aria-label="Bagikan">
    @foreach (['whatsapp' => 'ri-whatsapp-line', 'x' => 'ri-twitter-x-line', 'facebook' => 'ri-facebook-fill', 'telegram' => 'ri-telegram-2-line'] as $network => $icon)
        <a href="#" class="grid size-9 place-items-center border border-line text-base transition hover:border-raya hover:bg-raya hover:text-white" aria-label="Bagikan ke {{ ucfirst($network) }}"><i class="{{ $icon }}"></i></a>
    @endforeach
    <button type="button" data-copy-link class="grid size-9 place-items-center border border-line text-base transition hover:border-raya hover:bg-raya hover:text-white" aria-label="Salin tautan"><i class="ri-link"></i></button>
</div>
