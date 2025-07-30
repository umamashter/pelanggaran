@extends('layouts.main')
@section('title', 'Ubah Password')
@section('content')
    <div class="row d-flex">
        <div class="card col-md-2" style="opacity: 0"></div>
        <div class="card col-md-6 offset-lg-1">
            <div class="card-header bg-success bg-gradient text-white">
                <h3 class="mt-2">
                    Ubah Password
                </h3>
            </div>

            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="/guru/ubah-pass/{{ auth()->user()->id }}" method="post" id="change_pass_form">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="oldPasswordInput" class="form-label">Old Password</label>
                        <input name="old_password" type="password"
                            class="form-control @error('old_password') is-invalid @enderror" id="oldPasswordInput"
                            placeholder="Old Password">
                        @error('old_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="newPasswordInput" class="form-label">New Password</label>
                        <input name="new_password" type="password"
                            class="form-control @error('new_password') is-invalid @enderror" id="newPasswordInput"
                            placeholder="New Password">
                        @error('new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="confirmNewPasswordInput" class="form-label">Confirm New Password</label>
                        <input name="new_password_confirmation" type="password" class="form-control"
                            id="confirmNewPasswordInput" placeholder="Confirm New Password">
                    </div>
                    <div class="card-footer" style="padding: 10px 15px;">
                        <a class="btn btn-sm btn-secondary" href="/home">Kembali</a>
                        <button type="submit" class="btn btn-sm btn-success" id="btn-pass">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
