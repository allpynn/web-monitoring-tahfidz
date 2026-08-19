<x-tahfidz-layout>
    <x-slot name="header">
        Monitoring Hafalan Ananda
    </x-slot>
    <x-slot name="subtitle">
        Progres hafalan santri di bawah wali santri {{ auth()->user()->name }}.
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-10">
        @forelse($students as $student)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-xl shadow-lg shadow-emerald-500/20">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->name }}</h2>
                            <p class="text-sm text-gray-500">Dibimbing oleh: <span class="font-bold text-emerald-600">{{ $student->guru->name ?? 'Ustadz Belum Ditentukan' }}</span></p>
                        </div>
                    </div>
                    <a href="{{ route('parent.history.export', $student) }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Rekap
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <x-tahfidz.card title="Pencapaian">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900 dark:text-white">Juz {{ $student->current_juz }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Hafalan Terakhir</p>
                            </div>
                        </div>
                    </x-tahfidz.card>

                    <x-tahfidz.card title="Progres Target">
                        @php $progress = $student->target_progress; @endphp
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-end">
                                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $progress }}%</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ count($student->completed_juz) }}/{{ $student->target_juz }} Juz</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-1000 {{ $progress == 100 ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </x-tahfidz.card>

                    <x-tahfidz.card title="Ustadz Pendamping">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $student->guru->name ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic">Pembimbing</p>
                            </div>
                        </div>
                    </x-tahfidz.card>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                    <div class="lg:col-span-2">
                        <x-tahfidz.card title="Riwayat Terakhir" class="h-full flex flex-col">
                            <div class="overflow-x-auto flex-1">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-700 text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                            <th class="pb-2 px-3">Tanggal</th>
                                            <th class="pb-2 px-4">Materi Hafalan</th>
                                            <th class="pb-2 px-4">Status</th>
                                            <th class="pb-2 px-4">Catatan Guru</th>
                                            <th class="pb-2 pr-3 text-right">Pembimbing</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($student->memorizations->take(10) as $m)
                                            <tr>
                                                <td class="py-2.5 text-[10px] text-gray-500 font-black bg-gray-50/30 dark:bg-gray-900/10 px-3 rounded-l-xl uppercase whitespace-nowrap">
                                                    {{ $m->tanggal ? \Carbon\Carbon::parse($m->tanggal)->format('d M') : $m->created_at->format('d M') }}
                                                </td>
                                                <td class="py-2.5 px-4 font-bold text-gray-900 dark:text-white text-sm">
                                                    @if($m->is_present)
                                                        <div class="flex flex-col lg:flex-row lg:items-center gap-1">
                                                            <span class="text-emerald-700 dark:text-emerald-400">Jz.{{ $m->juz }} {{ $m->surah }}</span>
                                                            <span class="text-[10px] text-gray-400 font-medium">({{ $m->ayat }})</span>
                                                        </div>
                                                    @else
                                                        <span class="text-red-500 uppercase text-[10px] font-black italic">Tidak Setor Hafalan</span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 px-4">
                                                    @if($m->is_present)
                                                        <span class="px-2 py-0.5 {{ $m->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' }} text-[9px] font-black rounded-lg uppercase tracking-tighter">
                                                            {{ $m->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 px-4 text-xs text-gray-600 dark:text-gray-300 font-medium">
                                                    @if($m->notes)
                                                        <span class="italic">"{{ $m->notes }}"</span>
                                                    @else
                                                        <span class="text-gray-300 dark:text-gray-600">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 text-right pr-3 rounded-r-xl whitespace-nowrap">
                                                    <span class="text-[10px] text-gray-400 italic">{{ $m->guru->name ?? 'Guru' }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada riwayat hafalan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('parent.history.index', ['student_id' => $student->id]) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-gray-50 dark:bg-gray-900/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-200 group">
                                    Lihat riwayat lengkap
                                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </a>
                            </div>
                        </x-tahfidz.card>
                    </div>

                    <div class="lg:col-span-1 flex flex-col gap-6">

                        <x-tahfidz.card title="Kualitas Hafalan">
                            <div class="relative w-full aspect-square max-h-48 mx-auto flex items-center justify-center">
                                @php
                                    $totalQ = $student->quality_chart_data['lancar'] + $student->quality_chart_data['perbaikan'];
                                    $lancarPct = $totalQ > 0 ? round(($student->quality_chart_data['lancar'] / $totalQ) * 100) : 0;
                                @endphp
                                @if($totalQ > 0)
                                    <canvas id="qualityChart{{ $student->id }}" class="quality-chart" data-lancar="{{ $student->quality_chart_data['lancar'] }}" data-perbaikan="{{ $student->quality_chart_data['perbaikan'] }}"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                        <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $lancarPct }}%</span>
                                        <span class="text-[10px] text-gray-500 font-bold uppercase">Lancar</span>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">Belum ada data kualitas hafalan.</p>
                                @endif
                            </div>
                            <div class="flex justify-center gap-4 mt-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400 font-bold">Lancar ({{ $student->quality_chart_data['lancar'] }})</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400 font-bold">Perbaikan ({{ $student->quality_chart_data['perbaikan'] }})</span>
                                </div>
                            </div>
                        </x-tahfidz.card>

                        <x-tahfidz.card title="Ruang Komunikasi" class="flex flex-col">
                            
                            <div id="chat-box-{{ $student->id }}" class="h-52 overflow-y-auto mb-4 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-900/50 space-y-4 custom-scrollbar mt-2">
                                @forelse($student->messages ?? [] as $msg)
                                    <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} items-start">
                                        @if($msg->sender_id !== auth()->id())
                                            <div class="flex flex-col items-start max-w-[85%]">
                                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-2xl rounded-tl-none px-4 py-3 text-sm shadow-sm leading-relaxed">
                                                    {{ $msg->message }}
                                                </div>
                                                <span class="text-[10px] text-gray-400 mt-1.5 ml-1 font-bold">{{ $msg->created_at->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-end max-w-[85%]">
                                                <div class="bg-emerald-600 text-white rounded-2xl rounded-tr-none px-4 py-3 text-sm shadow-lg shadow-emerald-500/20 font-medium leading-relaxed">
                                                    {{ $msg->message }}
                                                </div>
                                                <span class="text-[10px] text-gray-400 mt-1.5 mr-1 font-bold">{{ $msg->created_at->format('H:i') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div id="empty-chat-{{ $student->id }}" class="h-full flex flex-col items-center justify-center py-20 opacity-40">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Belum ada percakapan</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            <form id="chat-form-{{ $student->id }}"
                                  data-student-id="{{ $student->id }}"
                                  
                                  data-action="{{ route('parent.messages.send', $student, false) }}"
                                  class="flex gap-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl">
                                @csrf
                                <input type="text" name="message" required placeholder="Tulis pesan ke Ustadz..."
                                       class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white placeholder:text-gray-300 font-medium"
                                       id="chat-input-{{ $student->id }}">
                                <button type="submit" class="w-12 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all shadow-lg shadow-emerald-500/20 active:scale-90 flex-shrink-0">
                                    <svg class="w-6 h-6 rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        </x-tahfidz.card>
                    </div>
                </div>
            </div>
            <div class="border-t-2 border-dashed border-gray-100 dark:border-gray-800"></div>
        @empty
            <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-800">
                <p class="text-gray-500">Anda tidak terhubung dengan data santri manapun. Silakan hubungi Admin.</p>
            </div>
        @endforelse

        @if($students->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $students->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        
        function createBubble(message, time, isSelf) {
            if (isSelf) {
                return `<div class="flex justify-end items-start">
                    <div class="flex flex-col items-end max-w-[85%]">
                        <div class="bg-emerald-600 text-white rounded-2xl rounded-tr-none px-4 py-3 text-sm shadow-lg shadow-emerald-500/20 font-medium leading-relaxed">${message}</div>
                        <span class="text-[10px] text-gray-400 mt-1.5 mr-1 font-bold">${time}</span>
                    </div>
                </div>`;
            } else {
                return `<div class="flex justify-start items-start">
                    <div class="flex flex-col items-start max-w-[85%]">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-2xl rounded-tl-none px-4 py-3 text-sm shadow-sm leading-relaxed">${message}</div>
                        <span class="text-[10px] text-gray-400 mt-1.5 ml-1 font-bold">${time}</span>
                    </div>
                </div>`;
            }
        }

        function scrollToBottom(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        document.addEventListener('DOMContentLoaded', function() {
            
            document.querySelectorAll('[id^="chat-box-"]').forEach(box => scrollToBottom(box));

            document.querySelectorAll('[id^="chat-form-"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const studentId = this.dataset.studentId;
                    const action = this.dataset.action;
                    const input = document.getElementById('chat-input-' + studentId);
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
                            const chatBox = document.getElementById('chat-box-' + studentId);
                            const emptyState = document.getElementById('empty-chat-' + studentId);
                            if (emptyState) emptyState.remove();

                            const now = new Date();
                            const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                            chatBox.insertAdjacentHTML('beforeend', createBubble(message, time, true));
                            scrollToBottom(chatBox);
                            input.value = '';
                        }
                    });
                });
            });

            document.querySelectorAll('.quality-chart').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const lancar = parseInt(canvas.dataset.lancar);
                const perbaikan = parseInt(canvas.dataset.perbaikan);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lancar', 'Perlu Perbaikan'],
                        datasets: [{ data: [lancar, perbaikan], backgroundColor: ['#10b981', '#fbbf24'], borderWidth: 0, hoverOffset: 4 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: { enabled: true } } }
                });
            });

            window.addEventListener('message-received', (e) => {
                const pesan = e.detail;
                const chatBox = document.getElementById('chat-box-' + pesan.student_id);
                if (!chatBox) return; 

                const emptyState = document.getElementById('empty-chat-' + pesan.student_id);
                if (emptyState) emptyState.remove();

                const sentAt = new Date(pesan.created_at);
                const time = sentAt.getHours().toString().padStart(2, '0') + ':' + sentAt.getMinutes().toString().padStart(2, '0');
                chatBox.insertAdjacentHTML('beforeend', createBubble(pesan.message, time, false));
                scrollToBottom(chatBox);
            });
        });
    </script>
    @endpush
</x-tahfidz-layout>
