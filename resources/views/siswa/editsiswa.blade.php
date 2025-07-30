<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-gradient fs-4 fw-bold" style="padding: 15px;">
                <h4 class="modal-title" id="myModalLabel">Edit Data</h4>
                <button class="btn btn-md btn-dark text-warning" data-bs-target="#exampleModalToggle2"
                    data-bs-toggle="modal" data-bs-dismiss="modal">Edit Password</button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="editsiswaform">
                    @csrf
                    <input type="hidden" name="student_id" id="student_id">
                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="name" id="name" required>
                        <label for="name">Nama</label>
                    </div>
                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="alamat" id="alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="no_telp" id="no_telp" required>
                        <label for="no_tel">Telepon</label>
                    </div>

                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="n_ayah" id="n_ayah" required>
                        <label for="n_ayah">Nama Ayah</label>
                    </div>
                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="n_ibu" id="n_ibu" required>
                        <label for="n_ibu">Nama Ibu</label>
                    </div>

                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="alamat_ortu" id="alamat_ortu" required>
                        <label for="alamat_ortu">Alamat Ortu</label>
                    </div>

                    <div class="form-floating mb-4 mt-1">
                        <input type="text" class="form-control" name="no_telp_rumah" id="no_telp_rumah" required>
                        <label for="no_telp_rumah">Nomer telepon rumah</label>
                    </div>
            </div>
            <div class="modal-footer" style="padding: 12px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-warning" id="btn-update"
                    onclick="editSiswa(event)">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Password --}}

<div class="modal fade" id="exampleModalToggle2" data-bs-keyboard="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="change_pass_form">
                <div class="modal-header bg-danger bg-gradient fs-4 fw-bold text-light" style="padding: 10px 15px;">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Ubah Password</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="old_password">Password Lama</label>
                        <input minlength=8 type="password" class="form-control" name="old_password" id="old_password"
                            required onkeydown="return (event.keyCode!=13);">
                    </div>
                    <div class="mt-2">
                        <label for="new_password">Password Baru</label>
                        <input minlength=8 type="password" class="form-control" name="new_password" id="new_password"
                            required onkeydown="return (event.keyCode!=13);">
                    </div>
                    <div class="mt-2">
                        <label for="password">Confirm Password</label>
                        <input minlength=8 type="password" class="form-control" name="password_confirm"
                            id="password_confirm" required onkeydown="return (event.keyCode!=13);">
                    </div>
                </div>

                <div class="modal-footer" style="padding: 10px 15px;">
                    <button class="btn btn-sm btn-secondary" data-bs-target="#myModal" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" onclick="editPass(event)" class="btn btn-sm btn-success"
                        id="btn-pass">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-floating>.form-control {
        height: 3.2rem;
        line-height: 1.25;
    }

    label {
        top: -5px;
        height: auto;
    }

    label.error {
        padding: 0;
        top: 56px;
        right: 0px !important;
        height: auto;
        opacity: 1;
        color: #ff3b3b;
        font-size: 15px;
    }
</style>
