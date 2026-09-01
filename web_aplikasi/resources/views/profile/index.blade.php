@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0D1B8C] tracking-tight">Profil Pengguna</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola informasi akun dan lokasi kerja Anda.</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 flex items-start gap-3 shadow-sm transition-all duration-300" x-data="{ show: true }" x-show="show" x-transition>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-bold text-sm">Terdapat kesalahan pada input Anda:</span>
            </div>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Profile Card --}}
    <div class="glass-card bg-white p-8 relative">
        <div class="wave-bg mix-blend-multiply opacity-30"></div>
        
        <form action="{{ route('profile.update') }}" method="POST" class="relative z-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Left Column: Editable Info --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Informasi Akun (Dapat Diubah)
                    </h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Pengguna</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow bg-slate-50 text-slate-800">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password Baru <span class="font-normal text-xs text-slate-500">(opsional, kosongi jika tidak ingin diubah)</span></label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow bg-slate-50 text-slate-800"
                               placeholder="********">
                    </div>
                </div>

                {{-- Right Column: Read Only Info --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Informasi Jabatan (Hanya Baca)
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">User ID</label>
                        <div class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-100 text-slate-600 cursor-not-allowed font-medium">
                            {{ $user->user_id }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Level Role</label>
                        <div class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-100 text-slate-600 cursor-not-allowed font-medium capitalize">
                            {{ str_replace('_', ' ', $user->role) }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi UP3</label>
                        <div class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-100 text-slate-600 cursor-not-allowed font-medium">
                            {{ $user->lokasi_UP3 ?? 'Belum Diatur' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi ULP</label>
                        <div class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-100 text-slate-600 cursor-not-allowed font-medium">
                            {{ $user->lokasi_ULP ?? 'Belum Diatur' }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-[#0D1B8C] rounded-lg hover:bg-blue-800 transition-colors shadow-md shadow-blue-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
