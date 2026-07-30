@if(file_exists(public_path('assets/img/logo.png')))
    <img src="{{ asset('assets/img/logo.png') }}" {{ $attributes }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center bg-emerald-100 text-emerald-700 rounded-lg p-2 font-bold whitespace-nowrap']) }}>
        Tahfidz Al-Mujahidin
    </div>
@endif
