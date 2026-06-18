<x-tahfidz-layout>
    <x-slot name="header">
        Pesan dari Orang Tua
    </x-slot>
    <x-slot name="subtitle">
        Kelola dan balas pesan koordinasi dari orang tua santri.
    </x-slot>

    <div class="mt-6" 
         x-on:message-received.window="handleMessageReceived($event.detail)"
         x-on:reply-success.window="handleReplySuccess($event.detail)"
         x-on:sync-unread.window="unreadMessages = $event.detail; updateSidebarBadge()"
         x-data="{ 
        openChat: null,
        showArchive: {{ $showArchive ? 'true' : 'false' }},
        unreadMessages: {
            @foreach($parent_messages as $msg)
                {{ $msg->student_id }}: {{ $msg->has_unread ? 'true' : 'false' }},
            @endforeach
        },
        updateSidebarBadge() {
            const badge = document.getElementById('sidebar-unread-badge');
            if (badge) {
                let count = Object.values(this.unreadMessages).filter(v => v === true).length;
                badge.innerText = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        },
        markAsRead(studentId) {
            if (this.openChat === studentId) {
                this.openChat = null;
            } else {
                this.openChat = studentId;
                if (this.unreadMessages[studentId]) {
                    fetch(`/guru/messages/${studentId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.unreadMessages[studentId] = false;
                        this.updateSidebarBadge();
                    });
                }
            }
        },
        handleMessageReceived(pesan) {
            // Update Alpine state for unread indicators
            if (this.openChat !== pesan.student_id) {
                this.unreadMessages[pesan.student_id] = true;
                this.updateSidebarBadge();
            } else {
                // If message arrives while chat is OPEN, mark as read on backend immediately
                fetch(`/guru/messages/${pesan.student_id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
            }
        },
        handleReplySuccess(studentId) {
            // Clear unread status when replying
            this.unreadMessages[studentId] = false;
            this.updateSidebarBadge();
        },
        toggleArchive() {
            this.showArchive = !this.showArchive;
            document.getElementById('archive-input').value = this.showArchive ? '1' : '0';
            updateList(document.getElementById('filter-form')); // Need updateList global
        }
    }">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[32px] overflow-hidden shadow-sm">
            <!-- Unified Header Content -->
            <div class="px-6 py-5 bg-gray-50/30 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700">
                <form action="{{ route('guru.messages') }}" method="GET" id="filter-form" class="flex items-center gap-4">
                    <input type="hidden" name="archive" id="archive-input" :value="showArchive ? '1' : '0'">
                    
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari pengirim atau nama santri..."
                               class="w-full bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11 py-3 font-medium text-gray-700 dark:text-gray-200"
                               oninput="debounceSubmit(this.form)">
                    </div>

                    <div class="w-48">
                        <select name="status" onchange="updateList(this.form)"
                                class="w-full bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold text-gray-700 dark:text-gray-200 py-3">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Pesan</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        </select>
                    </div>

                    <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                    <button type="button" @click="toggleArchive()"
                            :class="showArchive ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-500 hover:bg-gray-200'"
                            class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span x-text="showArchive ? 'Pesan Aktif' : 'Arsip Pesan'"></span>
                    </button>
                </form>
            </div>

            <!-- List Content -->
            <div class="p-6">
                <h5 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-6 px-1">Daftar Percakapan</h5>
                <div id="message-list-container">
                    @include('guru.messages.partials.list')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let debounceTimer;
            function debounceSubmit(form) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    updateList(form);
                }, 400);
            }

            async function updateList(form) {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const url = `${form.action}?${params.toString()}`;

                window.history.pushState({}, '', url);

                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await response.text();
                    document.getElementById('message-list-container').innerHTML = html;
                } catch (error) {
                    console.error('Failed to update list:', error);
                    form.submit();
                }
            }

            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    updateList(e.target);
                });
            }

            // AJAX reply form handler - delegated because list can be re-rendered
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form.classList.contains('guru-reply-form')) return;
                e.preventDefault();

                const action = form.dataset.action;
                const studentId = form.dataset.studentId;
                const input = form.querySelector('input[name="message"]');
                const message = input.value.trim();
                if (!message) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const socketId = window.Echo ? window.Echo.socketId() : null;

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Socket-ID': socketId,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ message: message }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const convBox = document.getElementById('conversation-' + studentId);
                        if (convBox) {
                            const now = new Date();
                            const time = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
                            convBox.insertAdjacentHTML('beforeend', `
                                <div class="flex justify-end">
                                    <div class="max-w-[85%] sm:max-w-[70%]">
                                        <div class="px-4 py-2.5 rounded-[20px] text-sm bg-emerald-600 text-white rounded-tr-none">${message}</div>
                                        <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 text-right font-bold">${time}</p>
                                    </div>
                                </div>
                            `);
                            convBox.scrollTop = convBox.scrollHeight;
                        }
                        
                        // Notify Alpine that reply was successful
                        window.dispatchEvent(new CustomEvent('reply-success', { detail: studentId }));

                        input.value = '';
                    }
                });
            });

            // Helper: append a bubble from a received real-time message
            function appendIncomingBubble(studentId, message, time) {
                const convBox = document.getElementById('conversation-' + studentId);
                if (!convBox) return false;
                convBox.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-start">
                        <div class="max-w-[85%] sm:max-w-[70%]">
                            <div class="px-4 py-2.5 rounded-[20px] text-sm bg-gray-100 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200 rounded-tl-none">${message}</div>
                            <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 text-left font-bold">${time}</p>
                        </div>
                    </div>
                `);
                convBox.scrollTop = convBox.scrollHeight;
                return true;
            }

            // Listen for Real-time incoming messages
            window.addEventListener('message-received', (e) => {
                const pesan = e.detail;
                const sentAt = new Date(pesan.created_at);
                const time = sentAt.getHours().toString().padStart(2,'0') + ':' + sentAt.getMinutes().toString().padStart(2,'0');

                const conversationIsOpen = appendIncomingBubble(pesan.student_id, pesan.message, time);

                if (!conversationIsOpen) {
                    // Refresh the list to move the new message thread to the top
                    updateList(document.getElementById('filter-form'));
                }
                
                // Alpine handler (unread dot & sidebar) is still triggered because it listens to the same window event
            });
        </script>
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #334155;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
</x-tahfidz-layout>