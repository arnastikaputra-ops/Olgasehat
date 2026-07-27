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
                        <label class="block text-sm font-semibold mb-2">Jam Pertemuan</label>
                        <select name="jam" class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            @forelse($timeSlotOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @empty
                                <option value="09:00">09:00 WIB</option>
                                <option value="10:00">10:00 WIB</option>
                                <option value="14:00">14:00 WIB</option>
                                <option value="16:00">16:00 WIB</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Metode Pembayaran</label>
                        <select name="bank_code" class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            <option value="BCA">Virtual Account BCA</option>
                            <option value="BRI">Virtual Account BRI</option>
                            <option value="BPD">Virtual Account BPD</option>
                            <option value="DANA">DANA</option>
                            <option value="GOPAY">GoPay</option>
                        </select>
                        <input type="hidden" name="metode_pembayaran" value="virtualAccount">
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(async response => {
                if (response.status === 401) {
                    throw new Error('Unauthorized');
                }
                const resData = await response.json();
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
});
</script>

@endsection
