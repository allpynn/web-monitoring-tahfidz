<div class="space-y-3 mt-4" id="message-list-content">
    @forelse($parent_messages as $msg)
        <div class="border border-gray-100 dark:border-gray-700/50 rounded-3xl overflow-hidden transition-all duration-300"
            :class="openChat === {{ $msg->student_id }} ? 'bg-emerald-50/50 dark:bg-emerald-900/10 ring-1 ring-emerald-100 dark:ring-emerald-800/30' : 'hover:bg-gray-100/50 dark:hover:bg-gray-800/60 bg-gray-50 dark:bg-gray-800/50'">

            <div class="p-4 cursor-pointer flex items-center justify-between"
                @click="markAsRead({{ $msg->student_id }})">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-100/50 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-gray-800 dark:text-gray-100 uppercase tracking-tight">
                            {{ $msg->sender->name ?? 'Orang Tua' }}
                        </span>
                        <span
                            class="text-[11px] font-bold text-gray-400 dark:text-gray-500 flex items-center gap-1.5 mt-0.5 uppercase tracking-wider">
                            Santri: {{ $msg->student->name ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div x-show="unreadMessages[{{ $msg->student_id }}]" class="w-3 h-3 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/40"></div>
                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 transition-transform duration-300"
                        :class="openChat === {{ $msg->student_id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <div x-show="openChat === {{ $msg->student_id }}" x-collapse x-cloak>
                <div class="p-4 bg-white/50 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-800">
                    <div id="conversation-{{ $msg->student_id }}"
                         class="space-y-4 mb-6 max-h-[500px] overflow-y-auto px-2 custom-scrollbar">
                        @php $lastDate = null; @endphp
                        @foreach($msg->conversation as $c)
                            @php $msgDate = $c->created_at->format('d M Y'); @endphp
                            @if($lastDate !== $msgDate)
                                <div class="flex justify-center my-6">
                                    <span class="bg-gray-50 dark:bg-gray-950/40 text-gray-400 dark:text-gray-500 text-[9px] px-3 py-1 rounded-full font-black uppercase tracking-widest border border-gray-100 dark:border-gray-800">
                                        {{ $c->created_at->isToday() ? 'Hari Ini' : ($c->created_at->isYesterday() ? 'Kemarin' : $msgDate) }}
                                    </span>
                                </div>
                                @php $lastDate = $msgDate; @endphp
                            @endif
                            <div class="flex {{ $c->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <div class="px-4 py-2.5 rounded-[20px] text-sm {{ $c->sender_id === auth()->id() ? 'bg-emerald-600 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200 rounded-tl-none' }}">
                                        {{ $c->message }}
                                    </div>
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 {{ $c->sender_id === auth()->id() ? 'text-right' : 'text-left' }} font-bold">
                                        {{ $c->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900/60 p-2 rounded-2xl border border-gray-100/50 dark:border-gray-800">
                        <form class="guru-reply-form flex-1 flex gap-2"
                              data-action="{{ route('guru.messages.reply', $msg, false) }}"
                              data-student-id="{{ $msg->student_id }}"
                              data-msg-id="{{ $msg->id }}">
                            @csrf
                            <input type="text" name="message" required placeholder="Tulis balasan..."
                                   class="flex-1 border-none bg-transparent text-sm focus:ring-0 placeholder-gray-400 dark:placeholder-gray-600 text-gray-700 dark:text-gray-200">
                            <button type="submit"
                                    class="p-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all shadow-md active:scale-95 group">
                                <svg class="w-5 h-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div
            class="flex flex-col items-center justify-center py-20 bg-gray-50/30 dark:bg-gray-900/10 rounded-[40px] border-2 border-dashed border-gray-100 dark:border-gray-800">
            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <h3 class="text-gray-900 dark:text-white font-bold opacity-50">Belum ada pesan</h3>
        </div>
    @endforelse
</div>

<script>
    // Sync Alpine state with the data from the server-rendered partial
    const unreadStates = {
        @foreach($parent_messages as $msg)
            {{ $msg->student_id }}: {{ $msg->has_unread ? 'true' : 'false' }},
        @endforeach
    };
    // Dispatch separate events so the parent Alpine component can update independently
    window.dispatchEvent(new CustomEvent('sync-unread', { detail: { unread: unreadStates } }));
    window.dispatchEvent(new CustomEvent('sync-archive-unread', { detail: {{ $archiveUnreadCount ?? 0 }} }));
    window.dispatchEvent(new CustomEvent('sync-active-students', { detail: [{{ implode(',', $activeInCurrentYear ?? []) }}] }));
</script>