@props([
    'title' => null,
    'message' => null,
    'icon' => 'success',
    'position' => 'top-end',
    'timer' => 2600,
    'showConfirmButton' => false,
    'showCloseButton' => true,
    'sessionKeys' => [
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
    ],
])

@php
    $alerts = [];

    if (! blank($message)) {
        $alerts[] = [
            'title' => $title,
            'text' => $message,
            'icon' => $icon,
            'position' => $position,
            'timer' => $timer,
            'showConfirmButton' => $showConfirmButton,
            'showCloseButton' => $showCloseButton,
        ];
    }

    $sessionSwal = session('swal');
    if (is_array($sessionSwal)) {
        $alerts[] = [
            'title' => $sessionSwal['title'] ?? $sessionSwal['heading'] ?? null,
            'text' => $sessionSwal['text'] ?? $sessionSwal['message'] ?? null,
            'icon' => $sessionSwal['icon'] ?? $icon,
            'position' => $sessionSwal['position'] ?? $position,
            'timer' => $sessionSwal['timer'] ?? $timer,
            'showConfirmButton' => $sessionSwal['showConfirmButton'] ?? $showConfirmButton,
            'showCloseButton' => $sessionSwal['showCloseButton'] ?? $showCloseButton,
        ];
    } elseif (! blank($sessionSwal)) {
        $alerts[] = [
            'title' => $title,
            'text' => $sessionSwal,
            'icon' => $icon,
            'position' => $position,
            'timer' => $timer,
            'showConfirmButton' => $showConfirmButton,
            'showCloseButton' => $showCloseButton,
        ];
    }

    foreach ($sessionKeys as $sessionKey => $defaultIcon) {
        $sessionMessage = session($sessionKey);

        if (blank($sessionMessage)) {
            continue;
        }

        $alerts[] = [
            'title' => match ($sessionKey) {
                'success' => 'Berhasil',
                'error' => 'Gagal',
                'warning' => 'Peringatan',
                'info' => 'Info',
                default => ucfirst($sessionKey),
            },
            'text' => $sessionMessage,
            'icon' => $defaultIcon,
            'position' => $position,
            'timer' => $timer,
            'showConfirmButton' => $showConfirmButton,
            'showCloseButton' => $showCloseButton,
        ];
    }
@endphp

@if (! empty($alerts))
    <script>
        (() => {
            const alerts = @json($alerts);
            const alreadyShownKey = '__laninaSweetAlertShown';

            const showAlerts = () => {
                if (window[alreadyShownKey]) {
                    return;
                }

                window[alreadyShownKey] = true;

                alerts.forEach((alert) => {
                    Swal.fire({
                        toast: true,
                        position: alert.position ?? @json($position),
                        icon: alert.icon ?? @json($icon),
                        title: alert.title ?? '',
                        text: alert.text ?? '',
                        showConfirmButton: alert.showConfirmButton ?? @json($showConfirmButton),
                        showCloseButton: alert.showCloseButton ?? @json($showCloseButton),
                        timer: alert.timer ?? @json($timer),
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-white/60',
                            title: 'font-semibold',
                        },
                    });
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showAlerts, { once: true });
            } else {
                showAlerts();
            }
        })();
    </script>
@endif
