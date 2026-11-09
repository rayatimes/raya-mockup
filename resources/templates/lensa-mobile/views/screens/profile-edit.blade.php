<div class="scr" data-sb="auto">
    <x-lensa-mobile::appbar title="Edit Profil" />
    <form class="scroll form" data-scroll data-profile-form>
        <div class="person person-compact">
            <x-lensa-mobile::avatar :person="$member" :size="84" />
            <button type="button" class="section-more">Ganti Foto</button>
        </div>
        <label class="field"><span>Nama</span><input type="text" value="{{ $member['name'] }}" autocomplete="name"></label>
        <label class="field"><span>Nama pengguna</span><input type="text" value="{{ $member['username'] }}" autocomplete="username"></label>
        <label class="field"><span>Email</span><input type="email" value="{{ $member['email'] }}" autocomplete="email"></label>
        <label class="field"><span>Bio</span><textarea rows="3">{{ $member['bio'] }}</textarea></label>
        <button type="submit" class="m-btn m-btn-solid">Simpan</button>
    </form>
</div>
