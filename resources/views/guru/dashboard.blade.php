<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Guru
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang kembali, {{ auth()->user()->gender === 'Perempuan' ? 'Ustadzah' : 'Ustadz' }}
        {{ auth()->user()->name }}.
    </x-slot>

    <x-slot name="header_actions">
        <form action="{{ route('guru.dashboard') }}" method="GET"
            class="flex items-center gap-4 bg-white/80 dark:bg-gray-800/80 p-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <select name="semester"
                class="rounded-lg border-gray-200 dark:border-gray-700 bg-transparent text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500"
                onchange="this.form.submit()">
                <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
            </select>
            <select name="academic_year"
                class="rounded-lg border-gray-200 dark:border-gray-700 bg-transparent text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500"
                onchange="this.form.submit()">
                @php
                    $startYear = now()->year - 3;
                @endphp
                @foreach(range($startYear, now()->year + 1) as $y)
                    @php $ay = $y . '/' . ($y + 1); @endphp
                    <option value="{{ $ay }}" {{ $academicYear == $ay ? 'selected' : '' }}>TA {{ $ay }}
                    </option>
                @endforeach
            </select>
        </form>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('guru.hafalan.create') }}"
            class="p-4 bg-emerald-600 dark:bg-emerald-700 text-white rounded-2xl flex items-center gap-4 hover:bg-emerald-700 transition shadow-lg">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <span class="block font-bold text-lg">Input Hafalan</span>
                <span class="text-xs text-emerald-100">Catat setoran hafalan hari ini</span>
            </div>
        </a>

        <a href="{{ route('guru.students.index') }}"
            class="p-4 bg-blue-600 dark:bg-blue-700 text-white rounded-2xl flex items-center gap-4 hover:bg-blue-700 transition shadow-lg">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div>
                <span class="block font-bold text-lg">Daftar Santri</span>
                <span class="text-xs text-blue-100">Kelola dan monitor bimbingan</span>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-8">
        <x-tahfidz.card title="Total Santri" :value="$stats['total_overall_santri']"
            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" /><path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" /></svg>' />
        <x-tahfidz.card title="Total Santri Diampu" :value="$stats['total_santri_diampu']"
            icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <x-tahfidz.card class="border-red-200 dark:border-red-900 shadow-red-50 dark:shadow-none bg-red-50/10">
            <x-slot name="title_slot">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <h5 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perhatian
                        Khusus (Alert)</h5>
                </div>
            </x-slot>
            <div class="space-y-3">
                @forelse($early_warnings as $warning)
                    <div
                        class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border-l-4 border-red-500">
                        <div>
                            <p class="font-bold text-sm text-red-900 dark:text-red-200">{{ $warning->name }}</p>
                            <p class="text-[11px] text-red-600 font-bold uppercase">{{ $warning->warning_reason }}</p>
                        </div>
                        <span
                            class="text-xs text-red-400 font-bold">{{ $warning->last_mem_date ? $warning->last_mem_date->diffForHumans() : '-' }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        Semua santri dalam kondisi baik, rutin setor.
                    </p>
                @endforelse
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="Top Pencapaian Target">
            <div class="space-y-4 mt-4">
                @forelse($top_targets as $top)
                    <div>
                        <div class="text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">
                            {{ $top->name }}
                        </div>
                        <div class="space-y-3 pl-2 border-l-2 border-gray-100 dark:border-gray-700">
                            @foreach($top->targets as $t)
                                @php
                                    $p = $top->getJuzProgress($t->target_juz);
                                @endphp
                                <div>
                                    <div
                                        class="flex justify-between text-[10px] mb-1 font-semibold uppercase tracking-tight">
                                        <span>Juz {{ $t->target_juz }}</span>
                                        <span class="{{ $p == 100 ? 'text-emerald-500' : 'text-gray-500' }}">{{ $p }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all duration-700
                                                                                        {{ $p == 100 ? 'bg-emerald-500' : ($p >= 50 ? 'bg-blue-400' : 'bg-red-300') }}"
                                            style="width: {{ $p }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 italic">Belum ada data target.</p>
                @endforelse
            </div>
        </x-tahfidz.card>
    </div>

    <div class="mb-8">
        <x-tahfidz.card title="Data Setoran Terakhir">
            <div class="overflow-x-auto">
                <table class="w-full text-left mt-2 text-sm">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">
                            <th class="pb-3">Santri</th>
                            <th class="pb-3">Tanggal</th>
                            <th class="pb-3">Hafalan</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_activities as $activity)
                            <tr class="border-b border-gray-50 dark:border-gray-800 group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="py-3">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 block truncate max-w-[200px]">
                                        {{ $activity->student->name }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="text-[11px] font-bold text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-md">
                                        {{ \Carbon\Carbon::parse($activity->tanggal)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-600 dark:text-gray-300 font-medium">
                                    @if($activity->is_present)
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">Jz.{{ $activity->juz }}</span> {{ $activity->surah }}
                                    @else
                                        <span class="text-red-400 italic text-xs">Absen / Tidak Setor</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    @if($activity->is_present)
                                        @php
                                            $statusClasses = [
                                                'Lancar' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                                'Perlu Perbaikan' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/10 dark:text-orange-400 border-orange-200 dark:border-orange-800',
                                            ];
                                            $currentClass = $statusClasses[$activity->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase border {{ $currentClass }}">
                                            {{ $activity->status }}
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-red-50 text-red-600 border border-red-100 italic">
                                            Absen
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center italic text-gray-400 py-8 bg-gray-50/50 dark:bg-gray-800/10 rounded-xl">
                                    Belum ada data setoran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tahfidz.card>
    </div>

    <div class="mt-8" x-data="{ 
        openChat: null,
        unreadMessages: {
            @foreach($parent_messages as $msg)
                {{ $msg->id }}: {{ $msg->has_unread ? 'true' : 'false' }},
            @endforeach
        },
        markAsRead(msgId, studentId) {
            if (this.openChat === msgId) {
                this.openChat = null;
            } else {
                this.openChat = msgId;
                if (this.unreadMessages[msgId]) {
                    fetch(`/guru/messages/${studentId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.unreadMessages[msgId] = false;
                    });
                }
            }
        }
    }">
        <x-tahfidz.card title="Pesan Orang Tua (Antrean)">
            <div class="space-y-3 mt-3">
                @forelse($parent_messages as $msg)
                    <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="openChat === {{ $msg->id }} ? 'ring-2 ring-emerald-500 shadow-lg' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40'">

                        <!-- Header (Preview) -->
                        <div class="p-4 cursor-pointer flex items-center justify-between"
                            @click="markAsRead({{ $msg->id }}, {{ $msg->student_id }})">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-black">
                                    {{ substr($msg->sender->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-tighter">
                                        {{ $msg->sender->name ?? 'Orang Tua' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-emerald-600">
                                        Santri: {{ $msg->student->name ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <template x-if="unreadMessages[{{ $msg->id }}]">
                                    <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                </template>
                                <svg class="w-5 h-5 text-gray-300 transition-transform duration-300"
                                    :class="openChat === {{ $msg->id }} ? 'rotate-180' : ''" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <div x-show="openChat === {{ $msg->id }}" x-collapse x-cloak>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="space-y-4 mb-4 max-h-[300px] overflow-y-auto px-2 custom-scrollbar">
                                    @foreach($msg->conversation as $c)
                                        <div
                                            class="flex {{ $c->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                            <div class="max-w-[80%]">
                                                <div
                                                    class="px-4 py-2.5 rounded-2xl text-sm {{ $c->sender_id === auth()->id() ? 'bg-emerald-600 text-white rounded-tr-none shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-tl-none shadow-sm' }}">
                                                    {{ $c->message }}
                                                </div>
                                                <p
                                                    class="text-[9px] text-gray-400 mt-1 {{ $c->sender_id === auth()->id() ? 'text-right' : 'text-left' }} font-bold">
                                                    {{ $c->created_at->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-3">
                                    <form action="{{ route('guru.messages.reply', $msg) }}" method="POST"
                                        class="flex-1 flex gap-2">
                                        @csrf
                                        <input type="text" name="message" required placeholder="Balas ke Orang Tua..."
                                            class="flex-1 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                        <button type="submit"
                                            class="p-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl transition-all shadow-md active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('guru.messages.destroy', $msg) }}" method="POST"
                                        onsubmit="return confirm('Selesaikan percakapan? Pesan akan dipindahkan dari antrean.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-xl transition-all"
                                            title="Selesaikan & Hapus dari Antrean">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-900/20 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-800">
                        <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        <p class="text-sm text-gray-400 font-medium italic">Tidak ada pesan</p>
                    </div>
                @endforelse
            </div>
        </x-tahfidz.card>
    </div>

@push('scripts')
        <script>
            // Any other scripts if needed
        </script>
    @endpush
</x-tahfidz-layout>