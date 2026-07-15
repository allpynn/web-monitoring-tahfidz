import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const userId = document.querySelector('meta[name="user-id"]')?.content;

if (userId) {
    window.Echo.private('user.' + userId)
        .listen('.message.sent', (e) => {
            console.log('Real-time event received:', e);
            
            // Ignore if we are the sender
            if (e.pesan.sender_id == userId) {
                console.log('Ignoring self-sent message event.');
                return;
            }
            
            const badge = document.getElementById('sidebar-unread-badge');
            if (badge) {
                // Only increment if we aren't currently viewing this message in an open chat
                // We'll dispatch the event and let the page handle its own badge if it wants,
                // but for now, we just increment for the global sidebar.
                let count = parseInt(badge.innerText) || 0;
                badge.innerText = count + 1;
                badge.classList.remove('hidden');
            }
            
            // Dispatch custom event for specific pages to handle
            window.dispatchEvent(new CustomEvent('message-received', { detail: e.pesan }));
        });
} else {
    console.warn('User ID meta tag not found. Real-time notifications disabled.');
}
