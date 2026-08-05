@extends('layouts.app')

@section('content')

@php
    $clinic = $clinic ?? $service->clinic;
    $primaryImage = $clinic->logo
        ? asset('fotoklinik/' . $clinic->logo)
        : asset('assets/klnk.png');
    $galleryImages = ($clinic->galleries ?? collect())->map(function ($item) {
        return strpos($item->foto ?? '', 'clinic_galleries') !== false
            ? asset('storage/' . $item->foto)
            : asset('fotoklinik/' . $item->foto);
    });
    $priceLabel = $service->tipe_harga === 'gratis'
        ? 'Gratis'
        : 'Rp ' . number_format($service->harga ?? 0, 0, ',', '.');
    $fasilitas = collect($clinic->fasilitas ?? []);
    $address = collect([$clinic->alamat, $clinic->kota, $clinic->provinsi, $clinic->kode_pos])
        ->filter()
        ->implode(', ');
    $doctors = ($clinic->doctors ?? collect())->filter(fn ($doc) => $doc->aktif);
    $timeSlotOptions = collect($timeSlots ?? [])->flatMap(function ($items, $day) {
        return $items->map(function ($item) use ($day) {
            return [
                'label' => ucfirst($day) . ' ' . $item['label'],
                'value' => $item['label'],
            ];
        });
    });
@endphp

<section class="relative overflow-hidden">
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700">
        <div class="container mx-auto px-6 py-16 text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-sm uppercase tracking-[0.4em] text-blue-200 mb-3">{{ ucfirst($service->kategori) }}</p>
                    <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4">{{ $service->nama }}</h1>
                    <p class="text-blue-100 flex items-center text-lg mb-3">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-200"></i>
                        {{ $address ?: 'Alamat belum tersedia' }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="inline-flex items-center bg-white/10 border border-white/20 px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-money-bill-wave mr-2"></i>{{ $priceLabel }}
                        </span>
                        <span class="inline-flex items-center bg-amber-400/20 border border-amber-300/40 text-amber-200 px-4 py-2 rounded-full text-sm font-bold shadow-sm">
                            <i class="fas fa-star text-amber-300 mr-2"></i> {{ $avgRating }} / 5.0 ({{ $totalReviews }} Ulasan)
                        </span>
                        @if($clinic->nomor_telepon)
                        <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-sm font-medium">
                            <i class="fas fa-phone mr-2"></i>{{ $clinic->nomor_telepon }}
                        </span>
                        @endif
                        <button type="button" onclick="openReviewModal('klinik', {{ $clinic->id }}, '{{ addslashes($clinic->nama) }}')" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold px-4 py-2 rounded-full shadow-md transition">
                            <i class="fas fa-star mr-2"></i> Beri Rating Klinik
                        </button>
                    </div>
                </div>
                <div class="hidden lg:block relative">
                    <img src="{{ $primaryImage }}" alt="{{ $clinic->nama }}" class="rounded-2xl shadow-2xl w-full h-72 object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<main class="container mx-auto px-4 sm:px-6 pt-12 pb-24 max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <section class="lg:col-span-8 space-y-8">
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-custom space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Tentang {{ $clinic->nama }}</h2>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $clinic->deskripsi ?? 'Deskripsi klinik belum ditambahkan oleh pengelola.' }}
                    </p>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-star text-green-500 mr-2"></i> Fasilitas
                    </h3>
                    @if($fasilitas->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($fasilitas as $item)
                                <div class="flex items-center bg-green-50 border border-green-100 rounded-lg px-4 py-3 text-gray-700 text-sm">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Pengelola belum menambahkan daftar fasilitas.</p>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i> Jadwal Operasional
                        </h3>
                        @if($clinic->hari_operasional)
                            <ul class="space-y-2 text-gray-700">
                                @foreach($clinic->hari_operasional as $day)
                                    <li class="flex justify-between border-b border-gray-100 pb-2">
                                        <span class="font-medium">{{ ucfirst($day) }}</span>
                                        <span>{{ $clinic->jam_buka ? date('H:i', strtotime($clinic->jam_buka)) : '00:00' }} - {{ $clinic->jam_tutup ? date('H:i', strtotime($clinic->jam_tutup)) : '24:00' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Jadwal operasional belum ditambahkan.</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clock text-blue-500 mr-2"></i> Jadwal Pertemuan
                        </h3>
                        @if(($timeSlots ?? collect())->isNotEmpty())
                            <div class="space-y-3">
                                @foreach(($timeSlots ?? collect()) as $day => $slots)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-2">{{ ucfirst($day) }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($slots as $slot)
                                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">{{ $slot['label'] }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">Penjadwalan dokter belum tersedia.</p>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user-md text-purple-500 mr-2"></i> Dokter yang Melayani
                    </h3>
                    @if(($servingDoctors ?? collect())->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($servingDoctors as $doctor)
                                <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl p-6 hover:shadow-lg hover:border-purple-300 transition-all duration-300">
                                    <div class="flex items-start space-x-4">
                                        @if($doctor->foto)
                                            <img src="{{ asset('fotodokter/' . $doctor->foto) }}" alt="{{ $doctor->nama_lengkap ?? $doctor->nama }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center border-2 border-purple-200">
                                                <i class="fas fa-user-md text-purple-600 text-lg"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="font-bold text-lg text-gray-900 leading-tight">{{ $doctor->nama_lengkap ?? $doctor->nama }}</h4>
                                                @if($doctor->gelar)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $doctor->gelar }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($doctor->spesialisasi)
                                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                                <i class="fas fa-stethoscope text-blue-500 mr-2"></i>
                                                <span class="font-medium">{{ $doctor->spesialisasi }}</span>
                                            </div>
                                            @endif
                                            @if($doctor->pengalaman)
                                            <div class="bg-white rounded-lg p-3 border border-gray-100">
                                                <div class="flex items-start text-sm">
                                                    <i class="fas fa-briefcase text-green-500 mr-2 mt-0.5"></i>
                                                    <div class="text-gray-700 leading-relaxed">
                                                        <span class="font-medium text-green-700 mb-1 block">Pengalaman:</span>
                                                        {{ Str::limit($doctor->pengalaman, 150) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-md text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 italic">Belum ada dokter yang terdaftar di klinik ini.</p>
                        </div>
                    @endif
                </div>

                @if($galleryImages->isNotEmpty())
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images text-pink-500 mr-2"></i> Galeri Klinik
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($galleryImages as $image)
                            <img src="{{ $image }}" alt="{{ $clinic->nama }}" class="rounded-xl object-cover w-full h-36">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-custom">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Kontak Darurat</h3>
                <div class="space-y-3">
                    @if($clinic->nomor_telepon)
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-600 mr-3"></i>
                            <span class="font-semibold text-gray-800">Informasi</span>
                        </div>
                        <span class="text-green-600 font-bold">{{ $clinic->nomor_telepon }}</span>
                    </div>
                    @endif
                    @if($clinic->email)
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <span class="font-semibold text-gray-800">Email</span>
                        </div>
                        <span class="text-blue-600 font-bold">{{ $clinic->email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-custom">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Lokasi Klinik</h3>
                <p class="text-gray-700">{{ $address ?: 'Alamat belum tersedia' }}</p>
                <div class="bg-gray-50 border border-gray-100 rounded-xl h-52 mt-4 flex items-center justify-center text-gray-400">
                    <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                </div>
            </div>

            <!-- Ulasan & Rating Klinik Section -->
            <div class="bg-white p-6 rounded-2xl shadow-custom space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-gray-100 pb-4 gap-4">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 flex items-center">
                            <i class="fas fa-star text-amber-400 mr-2"></i> Ulasan & Rating Klinik
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">Penilaian langsung dari pasien yang pernah berobat & konsultasi.</p>
                    </div>
                    <button type="button" onclick="openReviewModal('klinik', {{ $clinic->id }}, '{{ addslashes($clinic->nama) }}')" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-md transition flex items-center">
                        <i class="fas fa-edit mr-2"></i> Beri Rating
                    </button>
                </div>

                <!-- Rating Stats Summary Box -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 rounded-xl border border-amber-200/60 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-center bg-white px-4 py-2.5 rounded-xl shadow-sm border border-amber-100">
                            <span class="block text-3xl font-extrabold text-amber-500 leading-none">{{ $avgRating }}</span>
                            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">dari 5.0</span>
                        </div>
                        <div>
                            <div class="flex items-center text-amber-400 space-x-1 text-base">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - $avgRating < 1)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-xs text-gray-600 mt-1 font-medium">Berdasarkan <strong>{{ $totalReviews }} ulasan</strong> pasien terverifikasi</p>
                        </div>
                    </div>
                </div>

                <!-- List Review Item Cards -->
                @if(isset($clinicReviews) && $clinicReviews->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($clinicReviews as $rev)
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/70 space-y-2 hover:border-amber-200 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm overflow-hidden">
                                            @if($rev->foto)
                                                <img src="{{ asset(str_replace('public/', '', $rev->foto)) }}" alt="{{ $rev->nama }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($rev->nama ?? 'P', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-900 text-sm leading-tight">{{ $rev->nama }}</h5>
                                            <span class="text-xs text-gray-500">{{ $rev->company ?: 'Pasien Klinik' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex text-amber-400 text-xs">
                                            @for($s = 1; $s <= 5; $s++)
                                                <i class="{{ $s <= $rev->rate ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-gray-400 mt-0.5 block">{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Baru saja' }}</span>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed pt-1">
                                    "{{ $rev->ulasan }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <i class="fas fa-comment-alt text-gray-300 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm font-medium">Belum ada ulasan untuk klinik ini.</p>
                        <p class="text-xs text-gray-400 mt-1">Jadilah pasien pertama yang memberikan rating & ulasan!</p>
                    </div>
                @endif
            </div>
        </section>

        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-2xl shadow-custom sticky top-24">
                <h3 class="text-xl font-bold mb-5">Buat Janji Konsultasi</h3>
                <p class="text-sm text-blue-100 mb-4">Isi form di bawah, tim kami akan menghubungi melalui WhatsApp.</p>
                <form id="healthBookingForm" class="space-y-4">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Pilih Dokter</label>
                        <select name="doctor_id" class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            @forelse($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->nama_lengkap ?? $doctor->nama }}</option>
                            @empty
                                <option>Tidak ada dokter aktif</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Pilih Tanggal</label>
                        <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 flex items-center justify-between">
                            <span>Jam Pertemuan</span>
                            <span class="text-[11px] text-blue-200 font-normal"><i class="fas fa-search mr-1"></i> Ketik untuk cari</span>
                        </label>
                        
                        <!-- Searchable Combobox for Jam Pertemuan -->
                        <div class="relative" id="jamComboboxContainer">
                            <input type="hidden" name="jam" id="selectedJamValue" value="{{ $timeSlotOptions->first()['value'] ?? '09:00' }}" required>
                            
                            <div class="relative">
                                <input type="text" id="jamSearchInput" 
                                       value="{{ $timeSlotOptions->first()['label'] ?? '09:00 WIB' }}"
                                       placeholder="Ketik jam atau hari (cth: Senin 08:00, 14:00)..." 
                                       autocomplete="off"
                                       class="w-full px-4 py-2.5 pl-10 pr-8 rounded-lg text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 text-sm font-semibold shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-clock text-blue-600"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-search text-xs text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Dropdown List -->
                            <div id="jamOptionsList" class="absolute left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white rounded-lg shadow-2xl border border-gray-200 z-50 hidden text-gray-800 text-sm divide-y divide-gray-100">
                                @forelse($timeSlotOptions as $idx => $option)
                                    <div class="jam-option-item px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between transition {{ $idx == 0 ? 'bg-blue-50 font-bold' : '' }}" 
                                         data-value="{{ $option['value'] }}" 
                                         data-label="{{ $option['label'] }}">
                                        <span class="flex items-center">
                                            <i class="fas fa-clock text-blue-600 mr-2 text-xs"></i>
                                            <span class="jam-label-text">{{ $option['label'] }}</span>
                                        </span>
                                        <span class="text-xs text-blue-600 font-semibold bg-blue-100 px-2 py-0.5 rounded-full">Pilih</span>
                                    </div>
                                @empty
                                    <div class="jam-option-item px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between transition bg-blue-50 font-bold" data-value="09:00" data-label="09:00 WIB">
                                        <span><i class="fas fa-clock text-blue-600 mr-2"></i> 09:00 WIB</span>
                                    </div>
                                    <div class="jam-option-item px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between transition" data-value="10:00" data-label="10:00 WIB">
                                        <span><i class="fas fa-clock text-blue-600 mr-2"></i> 10:00 WIB</span>
                                    </div>
                                    <div class="jam-option-item px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between transition" data-value="14:00" data-label="14:00 WIB">
                                        <span><i class="fas fa-clock text-blue-600 mr-2"></i> 14:00 WIB</span>
                                    </div>
                                    <div class="jam-option-item px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between transition" data-value="16:00" data-label="16:00 WIB">
                                        <span><i class="fas fa-clock text-blue-600 mr-2"></i> 16:00 WIB</span>
                                    </div>
                                @endforelse
                                <div id="noJamFoundMsg" class="px-4 py-3 text-center text-gray-500 italic hidden text-xs">
                                    <i class="fas fa-info-circle mr-1"></i> Tidak ada jam / hari yang cocok
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Metode Pembayaran</label>
                        <select name="bank_code" id="selectBankCode" class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            @if(isset($paymentSettings) && $paymentSettings->count() > 0)
                                @foreach($paymentSettings as $ps)
                                    <option value="{{ $ps->bank_code }}" 
                                            data-name="{{ $ps->bank_name }}" 
                                            data-number="{{ $ps->account_number }}" 
                                            data-holder="{{ $ps->account_holder }}">
                                        {{ $ps->bank_name }} ({{ $ps->bank_code }})
                                    </option>
                                @endforeach
                            @else
                                <option value="BCA" data-name="Bank Central Asia (BCA)" data-number="88008819203847" data-holder="PT OlgaSehat Indonesia">Virtual Account BCA</option>
                                <option value="BRI" data-name="Bank Rakyat Indonesia (BRI)" data-number="88002819203847" data-holder="PT OlgaSehat Indonesia">Virtual Account BRI</option>
                                <option value="BPD" data-name="Bank BPD Bali" data-number="88014819203847" data-holder="PT OlgaSehat Indonesia">Virtual Account BPD</option>
                                <option value="DANA" data-name="DANA E-Wallet" data-number="8528819203847" data-holder="PT OlgaSehat Indonesia">DANA</option>
                                <option value="GOPAY" data-name="GoPay E-Wallet" data-number="70001819203847" data-holder="PT OlgaSehat Indonesia">GoPay</option>
                            @endif
                        </select>
                        <input type="hidden" name="metode_pembayaran" value="virtualAccount">
                    </div>

                    <!-- KARTU INFORMASI REKENING PEMBAYARAN -->
                    <div id="boxPaymentInfo" class="bg-blue-800/40 backdrop-blur-md p-4 rounded-xl border border-blue-400/30 text-white space-y-2 text-sm shadow-md">
                        <div class="flex items-center justify-between border-b border-blue-400/30 pb-2">
                            <span class="text-xs text-blue-200 uppercase tracking-wider font-bold"><i class="fas fa-university mr-1"></i> Transfer Ke:</span>
                            <span id="displayBankName" class="font-bold text-yellow-300">Bank Central Asia (BCA)</span>
                        </div>
                        
                        <div>
                            <span class="text-xs text-blue-200 block mb-1">Nomor Rekening / Virtual Account:</span>
                            <div class="flex items-center justify-between bg-white text-gray-900 px-3 py-2 rounded-lg font-mono font-bold text-base shadow">
                                <span id="displayAccountNumber" class="tracking-widest text-blue-700">88008819203847</span>
                                <button type="button" id="btnCopyAccountNumber" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-2.5 py-1.5 rounded-md flex items-center transition shadow-sm">
                                    <i class="fas fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs text-blue-200">Atas Nama:</span>
                            <span id="displayAccountHolder" class="font-semibold text-white">PT OlgaSehat Indonesia</span>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-blue-400/30 mt-2">
                            <span class="text-xs text-blue-200">Nominal Transfer:</span>
                            <span class="font-black text-base text-yellow-300">
                                @if($service->tipe_harga == 'gratis' || $service->harga == 0)
                                    Rp 0 (GRATIS)
                                @else
                                    Rp {{ number_format($service->harga, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Upload Bukti Transfer (Foto / Resi) <span class="text-red-300">* Wajib</span></label>
                        <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full text-xs text-blue-900 bg-white p-2 rounded-lg cursor-pointer" required />
                    </div>
                    <button type="submit" class="w-full bg-white text-blue-700 font-bold py-3 rounded-lg hover:bg-blue-50 transition shadow-lg">
                        <i class="fas fa-credit-card mr-2"></i> BAYAR & BUAT JANJI
                    </button>
                </form>
                <p class="text-xs text-blue-100 mt-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Admin akan menghubungi Anda untuk konfirmasi via WhatsApp.
                </p>
            </div>


        </aside>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('healthBookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if user is logged in
            @if(!Auth::check())
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan login terlebih dahulu untuk melakukan pemesanan jadwal.',
                    confirmButtonText: 'Login Sekarang',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#1d4ed8'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
                return;
            @endif

            const formData = new FormData(bookingForm);
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Mengirimkan pengajuan penjadwalan.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("health.booking.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                if (response.status === 401) {
                    throw new Error('Unauthorized');
                }
                const contentType = response.headers.get('content-type');
                let resData;
                if (contentType && contentType.includes('application/json')) {
                    resData = await response.json();
                } else {
                    const textContent = await response.text();
                    console.error('Non-JSON server response:', textContent);
                    throw new Error('Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.');
                }

                if (!response.ok) {
                    let errMsg = resData.message || 'Terjadi kesalahan saat memproses data.';
                    if (resData.errors) {
                        errMsg = Object.values(resData.errors).flat().join('<br>');
                    }
                    throw new Error(errMsg);
                }
                return resData;
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Berhasil!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: 'Lihat Riwayat'
                    }).then(() => {
                        window.location.href = '/riwayatkontrol';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Gagal',
                        html: data.message || 'Terjadi kesalahan saat memproses data.'
                    });
                }
            })
            .catch(error => {
                if (error.message === 'Unauthorized') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Sesi Anda telah habis. Silakan login kembali.',
                        confirmButtonText: 'Login'
                    }).then(() => {
                        window.location.href = '{{ route("login") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Gagal',
                        html: error.message || 'Terjadi kesalahan koneksi sistem.'
                    });
                }
            });
        });
    }

    // Searchable Combobox for Jam Pertemuan
    const jamSearchInput = document.getElementById('jamSearchInput');
    const jamOptionsList = document.getElementById('jamOptionsList');
    const selectedJamValue = document.getElementById('selectedJamValue');
    const jamOptionItems = document.querySelectorAll('.jam-option-item');
    const noJamFoundMsg = document.getElementById('noJamFoundMsg');

    if (jamSearchInput && jamOptionsList) {
        jamSearchInput.addEventListener('focus', function() {
            jamOptionsList.classList.remove('hidden');
        });

        jamSearchInput.addEventListener('click', function() {
            jamOptionsList.classList.remove('hidden');
        });

        jamSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let matchCount = 0;

            jamOptionsList.classList.remove('hidden');

            jamOptionItems.forEach(item => {
                const label = (item.getAttribute('data-label') || '').toLowerCase();
                const value = (item.getAttribute('data-value') || '').toLowerCase();

                if (label.includes(query) || value.includes(query)) {
                    item.classList.remove('hidden');
                    matchCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (noJamFoundMsg) {
                if (matchCount === 0) {
                    noJamFoundMsg.classList.remove('hidden');
                } else {
                    noJamFoundMsg.classList.add('hidden');
                }
            }
        });

        jamOptionItems.forEach(item => {
            item.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                const lbl = this.getAttribute('data-label');

                if (selectedJamValue) selectedJamValue.value = val;
                if (jamSearchInput) jamSearchInput.value = lbl;

                jamOptionsList.classList.add('hidden');

                jamOptionItems.forEach(i => i.classList.remove('bg-blue-50', 'font-bold'));
                this.classList.add('bg-blue-50', 'font-bold');
            });
        });

        document.addEventListener('click', function(e) {
            const container = document.getElementById('jamComboboxContainer');
            if (container && !container.contains(e.target)) {
                jamOptionsList.classList.add('hidden');
            }
        });
    }

    // Update dynamic payment info card
    const selectBankCode = document.getElementById('selectBankCode');
    const displayBankName = document.getElementById('displayBankName');
    const displayAccountNumber = document.getElementById('displayAccountNumber');
    const displayAccountHolder = document.getElementById('displayAccountHolder');
    const btnCopy = document.getElementById('btnCopyAccountNumber');

    function updatePaymentCardDisplay() {
        if (!selectBankCode) return;
        const selectedOption = selectBankCode.options[selectBankCode.selectedIndex];
        if (selectedOption) {
            const name = selectedOption.getAttribute('data-name') || selectedOption.text;
            const number = selectedOption.getAttribute('data-number') || '-';
            const holder = selectedOption.getAttribute('data-holder') || 'PT OlgaSehat Indonesia';

            if (displayBankName) displayBankName.textContent = name;
            if (displayAccountNumber) displayAccountNumber.textContent = number;
            if (displayAccountHolder) displayAccountHolder.textContent = holder;
        }
    }

    if (selectBankCode) {
        selectBankCode.addEventListener('change', updatePaymentCardDisplay);
        updatePaymentCardDisplay();
    }

    if (btnCopy) {
        btnCopy.addEventListener('click', function(e) {
            if (e) e.preventDefault();
            const numText = displayAccountNumber ? displayAccountNumber.textContent.trim() : '';
            if (numText) {
                navigator.clipboard.writeText(numText).then(() => {
                    const origHtml = btnCopy.innerHTML;
                    btnCopy.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin!';
                    btnCopy.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    btnCopy.classList.add('bg-green-600', 'hover:bg-green-700');
                    setTimeout(() => {
                        btnCopy.innerHTML = origHtml;
                        btnCopy.classList.remove('bg-green-600', 'hover:bg-green-700');
                        btnCopy.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    }, 2000);
                }).catch(() => {
                    // Fallback copy
                    const tempInput = document.createElement('input');
                    tempInput.value = numText;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    alert('Nomor Rekening Tersalin: ' + numText);
                });
            }
        });
    }
});
</script>

@endsection
