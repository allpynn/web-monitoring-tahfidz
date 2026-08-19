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

// Toast notification helper
function showRealtimeToast(title, message) {
    let container = document.getElementById('realtime-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'realtime-toast-container';
        container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border border-emerald-500/30 text-gray-800 dark:text-gray-100 px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 transition-all duration-300 transform translate-y-2 opacity-0';

    toast.innerHTML = `
        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div class="flex-1 text-xs">
            <div class="font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">${title}</div>
            <div class="font-medium text-gray-600 dark:text-gray-300 mt-0.5">${message}</div>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// Silent background refresh for seamless DOM updates without page reload
let refreshTimeout = null;
function triggerRealtimeDOMUpdate() {
    clearTimeout(refreshTimeout);
    refreshTimeout = setTimeout(async () => {
        try {
            const url = window.location.href;
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTable = doc.getElementById('table-container');
            const currentTable = document.getElementById('table-container');

            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
                return;
            }

            const newContent = doc.getElementById('main-content-container');
            const currentContent = document.getElementById('main-content-container');

            if (newContent && currentContent) {
                currentContent.innerHTML = newContent.innerHTML;
            }
        } catch (e) {
            console.error('Real-time DOM swap error:', e);
        }
    }, 250);
}

// Subscribe to public real-time update channels
window.Echo.channel('student-updates')
    .listen('.student.updated', (e) => {
        const actionLabel = e.action === 'created' ? 'Ditambahkan' : (e.action === 'updated' ? 'Diperbarui' : 'Dihapus');
        showRealtimeToast('Real-Time Santri', `Data Santri ${e.studentName || ''} telah ${actionLabel.toLowerCase()}.`);
        triggerRealtimeDOMUpdate();
        window.dispatchEvent(new CustomEvent('realtime-student-updated', { detail: e }));
    });

window.Echo.channel('guru-updates')
    .listen('.guru.updated', (e) => {
        const actionLabel = e.action === 'created' ? 'Ditambahkan' : (e.action === 'updated' ? 'Diperbarui' : 'Dihapus');
        showRealtimeToast('Real-Time Guru', `Data Guru ${e.guruName || ''} telah ${actionLabel.toLowerCase()}.`);
        triggerRealtimeDOMUpdate();
        window.dispatchEvent(new CustomEvent('realtime-guru-updated', { detail: e }));
    });

window.Echo.channel('parent-updates')
    .listen('.parent.updated', (e) => {
        const actionLabel = e.action === 'created' ? 'Ditambahkan' : (e.action === 'updated' ? 'Diperbarui' : 'Dihapus');
        showRealtimeToast('Real-Time Orang Tua', `Data Orang Tua ${e.parentName || ''} telah ${actionLabel.toLowerCase()}.`);
        triggerRealtimeDOMUpdate();
        window.dispatchEvent(new CustomEvent('realtime-parent-updated', { detail: e }));
    });

window.Echo.channel('hafalan-updates')
    .listen('.hafalan.updated', (e) => {
        showRealtimeToast('Real-Time Hafalan', e.message || 'Setoran hafalan baru telah dicatat.');
        triggerRealtimeDOMUpdate();
        window.dispatchEvent(new CustomEvent('realtime-hafalan-updated', { detail: e }));
    });

// Private message notifications
const userId = document.querySelector('meta[name="user-id"]')?.content;

if (userId) {
    window.Echo.private('user.' + userId)
        .listen('.message.sent', (e) => {
            if (e.pesan.sender_id == userId) return;

            // Only increment directly if not on guru messages page (where handleMessageReceived updates it with full precision)
            if (!document.getElementById('message-list-container')) {
                const badge = document.getElementById('sidebar-unread-badge');
                if (badge) {
                    let count = parseInt(badge.innerText) || 0;
                    badge.innerText = count + 1;
                    badge.classList.remove('hidden');
                }
            }

            window.dispatchEvent(new CustomEvent('message-received', { detail: e.pesan }));
        });
}
