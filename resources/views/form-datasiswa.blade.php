@extends('layouts.data')
@section('title', 'Data Siswa & Orang tua')
@section('datas')
    <style>
        input[type="text"],
        input[type="date"],
        select {
            border-radius: 0 !important;
        }
    </style>
    <div class="container mb-5" style="margin-top: 2%; margin-bottom: 2%; height:100%;">
        <div class="animate__animated animate__fadeInDown login-container-wrapper clearfix bg-secondary">
            <div class="welcome">Data Siswa</div>
            <form class="form-horizontal login-form" method="POST" action="{{ route('siswa') }}">
                @csrf

                <div class="all-data">
                    <div class="siswa col-lg-7 col-sm-12">
                        {{-- hidden nisn --}}
                        <input id="nisn" type="hidden" class="form-control input-lg @error('nisn') is-invalid @enderror"
                            name="nisn" value="{{ Auth::user()->nisn }}" required autocomplete="nisn" readonly>

                        @error('nisn')
                            <span class="invalid-feedback ps-1" role="alert">
                                <strong
                                    style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                            </span>
                        @enderror

                        {{-- nama --}}
                        <div class="form-group relative1">
                            <input id="nama" type="text"
                                class="form-control input-lg @error('nama') is-invalid @enderror" name="nama"
                                value="{{ old('nama') }}" required autocomplete="nama" placeholder="Nama Siswa" autofocus
                                required>

                            @error('nama')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- ttl --}}
                        <div class="form-group relative">
                            <div class="input-group">
                                <input id="ttl" name="ttl" type="text"
                                    class="form-control input-lg @error('ttl') is-invalid @enderror"
                                    value="{{ old('ttl') }}" placeholder="Tempat Lahir" autocomplete="ttl" required>

                                <input type="date" id="date" name="date" class="form-control input-lg"
                                    placeholder="dd-mm-yyyy" required>
                            </div>

                            @error('ttl')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="inner">
                            {{-- jk --}}
                            <div class="form-group relative one">
                                <select
                                    class="form-select form-select-md form-select-solid input-lg @error('jk') is-invalid @enderror"
                                    name="jk" id="jk" value="{{ old('jk') }}" autocomplete="jk" required>
                                    <option disabled selected value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" @if (old('jk') == 'Laki-laki') {{ 'selected' }} @endif>
                                        Laki-laki</option>
                                    <option value="Perempuan" @if (old('jk') == 'Perempuan') {{ 'selected' }} @endif>
                                        Perempuan</option>
                                </select>

                                @error('jk')
                                    <span class="invalid-feedback ps-1" role="alert">
                                        <strong
                                            style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- agama --}}
                            <div class="form-group relative two">
                                <select
                                    class="form-select form-select-md form-select-solid input-lg @error('agama') is-invalid @enderror"
                                    name="agama" id="agama" value="{{ old('agama') }}" autocomplete="agama"
                                    required>
                                    <option disabled value="" selected>Pilih Agama</option>
                                    <option value="Islam" @if (old('agama') == 'Islam') {{ 'selected' }} @endif>
                                        Islam</option>
                                    <option value="Kristen" @if (old('agama') == 'Kristen') {{ 'selected' }} @endif>
                                        Kristen</option>
                                    <option value="Katolik" @if (old('agama') == 'Katolik') {{ 'selected' }} @endif>
                                        Katolik</option>
                                    <option value="Hindu" @if (old('agama') == 'Hindu') {{ 'selected' }} @endif>
                                        Hindu</option>
                                    <option value="Buddha" @if (old('agama') == 'Buddha') {{ 'selected' }} @endif>
                                        Buddha</option>
                                    <option value="Konghucu" @if (old('agama') == 'Konghucu') {{ 'selected' }} @endif>
                                        Konghucu</option>
                                </select>

                                @error('agama')
                                    <span class="invalid-feedback ps-1" role="alert">
                                        <strong
                                            style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        {{-- alamat --}}
                        <div class="form-group relative">
                            <input id="alamat" type="text"
                                class="form-control input-lg @error('alamat') is-invalid @enderror" name="alamat"
                                value="{{ old('alamat') }}" required autocomplete="alamat" placeholder="Masukkan Alamat">

                            @error('alamat')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="inner">
                            {{-- no_telp --}}
                            <div class="form-group relative one">
                                <input id="no_telp" type="text"
                                    class="form-control input-lg @error('no_telp') is-invalid @enderror" name="no_telp"
                                    value="{{ old('no_telp') }}" required autocomplete="no_telp"
                                    placeholder="Telepon Siswa">

                                @error('no_telp')
                                    <span class="invalid-feedback ps-1" role="alert">
                                        <strong
                                            style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- kelas --}}
                            <div class="form-group relative two">
                                <select
                                    class="form-select form-select-md form-select-solid input-lg @error('kelas') is-invalid @enderror"
                                    name="kelas" id="kelas" required autocomplete="kelas">
                                    <option disabled selected value="">Pilih kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('kelas') == $item->id) {{ 'selected' }} @endif>
                                            {{ $item->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('kelas')
                                    <span class="invalid-feedback ps-1" role="alert">
                                        <strong
                                            style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="ortu col-lg-5 col-sm-12">
                        {{-- nama_ayah --}}
                        <div class="form-group relative">
                            <input id="n_ayah" type="text"
                                class="form-control input-lg @error('n_ayah') is-invalid @enderror" name="n_ayah"
                                value="{{ old('n_ayah') }}" required autocomplete="n_ayah" placeholder="Nama Ayah">

                            @error('n_ayah')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- nama_ibu --}}
                        <div class="form-group relative">
                            <input id="n_ibu" type="text"
                                class="form-control input-lg @error('n_ibu') is-invalid @enderror" name="n_ibu"
                                value="{{ old('n_ibu') }}" required autocomplete="n_ibu" placeholder="Nama Ibu">

                            @error('n_ibu')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- alamat_ortu --}}
                        <div class="form-group relative">
                            <input id="alamat_ortu" type="text"
                                class="form-control input-lg @error('alamat_ortu') is-invalid @enderror"
                                name="alamat_ortu" value="{{ old('alamat_ortu') }}" required autocomplete="alamat_ortu"
                                placeholder="Alamat Orang Tua">

                            @error('alamat_ortu')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- telepon_rumah --}}
                        <div class="form-group relative">
                            <input id="no_telp_rumah" type="text"
                                class="form-control input-lg @error('no_telp_rumah') is-invalid @enderror"
                                name="no_telp_rumah" value="{{ old('no_telp_rumah') }}" required
                                autocomplete="no_telp_rumah" placeholder="Telepon Rumah">

                            @error('no_telp_rumah')
                                <span class="invalid-feedback ps-1" role="alert">
                                    <strong
                                        style="color: darkred; text-shadow: 0 0 5px #fff;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group in-btn mt-3">
                    <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Kirim Data') }}</button>
                </div>
            </form>

            <div class="text-center">
                <label>ingin keluar?
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </label>
            </div>
        </div>
    </div>
@endsection
