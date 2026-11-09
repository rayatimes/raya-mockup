@php
    $icons = ['home' => 'ri-home-5', 'saved' => 'ri-bookmark', 'notifications' => 'ri-notification-3', 'profile' => 'ri-user-3'];
    $paths = ['home' => '/', 'saved' => '/tersimpan', 'notifications' => '/notifikasi', 'profile' => '/profil'];
    $notificationIds = implode(',', array_column($rt->mobileNotifications(), 'id'));
@endphp
<nav class="tabbar" data-tabbar data-notif-ids="{{ $notificationIds }}" aria-label="Menu bawah">
    @foreach ($roots as $key => $label)
        <a href="{{ $rt->url($paths[$key]) }}" data-tab-link="{{ $key }}" @if ($key === $activeTab) aria-current="page" @endif class="tabbar-item">
            <span class="tabbar-icon">
                <i class="{{ $icons[$key] }}-line tabbar-off"></i><i class="{{ $icons[$key] }}-fill tabbar-on"></i>
                @if ($key === 'notifications')<span class="tabbar-badge" data-badge hidden></span>@endif
            </span>
            {{ $label }}
        </a>
    @endforeach
</nav>
