<section class="bg-ink p-5 text-white dark:bg-sunk">
    <i class="ri-mail-open-line text-2xl text-sun"></i>
    <h2 class="mt-2 font-serif text-xl font-semibold">Ringkasan pagi RayaTimes</h2>
    <p class="mt-1.5 text-sm text-white/70">Lima berita terpenting dan konteksnya, dikirim ke surel Anda setiap pukul 06.00 WIB.</p>
    <form class="mt-4 flex" onsubmit="event.preventDefault(); this.querySelector('button').textContent = 'Terdaftar ✓';">
        <label for="nl-email" class="sr-only">Alamat surel</label>
        <input id="nl-email" type="email" required placeholder="nama@surel.com" class="min-w-0 flex-1 border border-white/20 bg-white/5 px-3 py-2.5 text-sm text-white placeholder:text-white/40 focus:border-sun focus:outline-none">
        <button class="bg-raya px-4 text-sm font-semibold transition hover:bg-sun hover:text-ink">Daftar</button>
    </form>
</section>
