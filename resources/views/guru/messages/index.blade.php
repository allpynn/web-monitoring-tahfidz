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
         x-on:sync-unread.window="unreadMessages = $event.detail.unread; updateSidebarBadge()"
         x-on:sync-archive-unread.window="archiveUnreadCount = $event.detail"
         x-on:sync-active-students.window="activeStudentIds = $event.detail"
         x-data="{ 
        openChat: null,
        showArchive: {{ $showArchive ? 'true' : 'false' }},
        archiveUnreadCount: {{ $archiveUnreadCount }},
        activeStudentIds: [{{ implode(',', $activeInCurrentYear ?? []) }}],
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
                        // Decrement archive badge when reading an archived student's message
                        if (this.showArchive && this.archiveUnreadCount > 0) {
                            this.archiveUnreadCount--;
                        }
                    });
                }
            }
        },
        handleMessageReceived(pesan) {
            // Update Alpine state for unread indicators
            if (this.openChat !== pesan.student_id) {
                this.unreadMessages[pesan.student_id] = true;
                this.updateSidebarBadge();
                // If sender's student is archived (not in active year), increment archive badge
                if (!this.activeStudentIds.includes(pesan.student_id) && !this.showArchive) {
                    this.archiveUnreadCount++;
                }
            } else {
                // If message arrives while chat is open, mark as read on backend immediately
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
            if (this.unreadMessages[studentId]) {
                this.unreadMessages[studentId] = false;
                this.updateSidebarBadge();
                // If replying in archive tab, decrement archive badge too
                if (this.showArchive && this.archiveUnreadCount > 0) {
                    this.archiveUnreadCount--;
                }
            }
        },
        toggleArchive() {
            this.showArchive = !this.showArchive;
            // Optimistically clear archive badge when entering archive tab
            if (this.showArchive) {
                this.archiveUnreadCount = 0;
            }
            document.getElementById('archive-input').value = this.showArchive ? '1' : '0';
            updateList(document.getElementById('filter-form'));
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

                    <div class="relative">
                        <button type="button" @click="toggleArchive()"
                                :class="showArchive ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-500 hover:bg-gray-200'"
                                class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <span x-text="showArchive ? 'Pesan Aktif' : 'Arsip Pesan'"></span>
                        </button>
                        {{-- Archive unread badge: appears when there are unread messages from past academic year students --}}
                        <span x-show="!showArchive && archiveUnreadCount > 0"
                              x-text="archiveUnreadCount > 9 ? '9+' : archiveUnreadCount"
                              class="absolute -top-2 -right-2 min-w-[20px] h-5 px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-lg shadow-red-500/40 ring-2 ring-white dark:ring-gray-800 animate-pulse"
                              style="display: none;">
                        </span>
                    </div>
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

            // Move a thread to the top by CSS order (no DOM manipulation = Alpine.js safe)
            function bringThreadToTop(studentId) {
                const list = document.getElementById('message-list-content');
                if (!list) return;
                const card = list.querySelector(`.thread-card[data-student-id="${studentId}"]`);
                if (!card) return;

                // Find the current minimum order value in the list
                let minOrder = Infinity;
                list.querySelectorAll('.thread-card').forEach(c => {
                    const o = parseInt(c.style.order ?? '0');
                    if (o < minOrder) minOrder = o;
                });

                const cardOrder = parseInt(card.style.order ?? '0');

                // Already at top (or only card), nothing to do
                if (cardOrder <= minOrder) return;

                // Set order below current minimum → visually moves to top
                card.style.order = (minOrder - 1).toString();

                // Subtle highlight flash to indicate movement
                card.style.boxShadow = '0 0 0 2px rgba(16, 185, 129, 0.35)';
                card.style.transition = 'box-shadow 0.4s ease';
                setTimeout(() => {
                    card.style.boxShadow = '';
                    setTimeout(() => { card.style.transition = ''; }, 400);
                }, 700);
            }

            function sendGuruReply(form) {
                const action = form.dataset.action;
                const studentId = parseInt(form.dataset.studentId);
                const input = form.querySelector('input[name="message"]');
                const message = input.value.trim();
                if (!message) return;

                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const socketId = window.Echo ? window.Echo.socketId() : null;

                input.value = '';

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

                        // Move this thread to the top of the list
                        bringThreadToTop(studentId);
                    } else {
                        // Restore message if failed
                        input.value = message;
                    }
                })
                .catch(() => { input.value = message; })
                .finally(() => { if (submitBtn) submitBtn.disabled = false; });
            }

            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form.classList.contains('guru-reply-form')) return;
                e.preventDefault();
                e.stopPropagation();
                sendGuruReply(form);
            });

            // Handle Enter key on reply inputs
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Enter') return;
                const input = e.target;
                if (!input.matches('.guru-reply-form input[name="message"]')) return;
                e.preventDefault();
                const form = input.closest('.guru-reply-form');
                if (form) sendGuruReply(form);
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
                    // Thread not open — refresh list so thread appears (may be a new parent)
                    const cardExists = document.querySelector(`#message-list-content .thread-card[data-student-id="${pesan.student_id}"]`);
                    if (cardExists) {
                        // Thread already in list, just move it to top
                        bringThreadToTop(pesan.student_id);
                    } else {
                        // Brand new thread — refresh list to add it
                        updateList(document.getElementById('filter-form'));
                    }
                } else {
                    // Chat was open — still bring thread to top
                    bringThreadToTop(pesan.student_id);
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