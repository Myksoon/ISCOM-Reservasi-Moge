@php
    $booking = $booking ?? null;
    $jam = fn ($nilai) => $nilai ? substr($nilai, 0, 5) : '';
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="nama" class="form-label">Nama pemesan</label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $booking?->nama) }}" autocomplete="name" required>
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="no_hp" class="form-label">Nomor WhatsApp</label>
        <input type="tel" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $booking?->no_hp) }}" placeholder="081234567890" autocomplete="tel" required>
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="moge" class="form-label">Motor</label>
        <select name="moge" id="moge" class="form-select @error('moge') is-invalid @enderror" required>
            <option value="">Pilih motor</option>
            @foreach(config('moge') as $motor)
                <option value="{{ $motor['nama'] }}" @selected(old('moge', $booking?->moge ?? request('moge')) === $motor['nama'])>
                    {{ $motor['nama'] }} (Rp {{ number_format($motor['harga'], 0, ',', '.') }} per hari)
                </option>
            @endforeach
        </select>
        @error('moge')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<fieldset class="mb-4">
    <legend>Mulai sewa</legend>
    <div class="row g-3">
        <div class="col-sm-6">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $booking?->tanggal) }}" required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <label for="jam" class="form-label">Jam</label>
            <input type="time" name="jam" id="jam" class="form-control @error('jam') is-invalid @enderror" value="{{ old('jam', $jam($booking?->jam)) }}" required>
            @error('jam')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</fieldset>

<fieldset class="mb-4">
    <legend>Pengembalian</legend>
    <div class="row g-3">
        <div class="col-sm-6">
            <label for="tanggal_selesai" class="form-label">Tanggal</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $booking?->tanggal_selesai ?? $booking?->tanggal) }}" required>
            @error('tanggal_selesai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <label for="jam_selesai" class="form-label">Jam</label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', $jam($booking?->jam_selesai)) }}" required>
            @error('jam_selesai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</fieldset>

@if($booking)
    <div class="mb-4">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(\App\Models\Booking::STATUS as $kunci => $label)
                <option value="{{ $kunci }}" @selected(old('status', $booking->status) === $kunci)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

<div class="mb-4">
    <label for="catatan" class="form-label">Catatan <span class="fw-normal text-secondary">(boleh kosong)</span></label>
    <textarea name="catatan" id="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror" placeholder="Misalnya: minta helm full face ukuran L">{{ old('catatan', $booking?->catatan) }}</textarea>
    @error('catatan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<script>
    // tanggal kembali tidak boleh sebelum tanggal mulai
    const mulai = document.getElementById('tanggal');
    const kembali = document.getElementById('tanggal_selesai');
    const samakan = () => {
        kembali.min = mulai.value;
        if (kembali.value < mulai.value) kembali.value = mulai.value;
    };
    mulai.addEventListener('change', samakan);
    samakan();
</script>
