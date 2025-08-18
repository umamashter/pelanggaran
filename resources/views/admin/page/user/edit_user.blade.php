<div class="modal fade" id="myModal" tabindex="-1" role="dialog"  data-bs-keyboard="false"
aria-hidden="true">
<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-warning bg-gradient fs-4 fw-bold" style="padding: 15px;">
            <h4 class="modal-title" id="myModalLabel">Edit User</h4>
            <button class="btn btn-md btn-dark text-warning" data-bs-target="#exampleModalToggle2"
            data-bs-toggle="modal" data-bs-dismiss="modal">Edit Password</button>
        </div>
        <div class="modal-body">
            <form action="" method="post" id="editform">
                @csrf
                <div class="form-floating mb-4" id="nisn-group" >
                    <input minlength=8 type="text" class="form-control" name="nisn" id="nisn" required 
                    autofocus>
                    <label for="nisn">NISN</label>
                </div>
                <div class="form-floating mb-4 mt-1">
                    
                    <input type="text" class="form-control" name="name" id="name" required>
                    <label for="name">Nama</label>
                </div>
                <div class="form-floating mb-4 mt-1">
                    <input type="text" class="form-control" name="email" id="email" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-2 mt-1">
                    <select class="form-select" id="role" name="role" required>
                        <option selected value="" disabled>Pilih Role</option>
                        <option value="1">Admin</option>
                        <option value="2">Guru</option>
                        <option value="3">Siswa</option>                        
                    </select>
                    <label for="role">Role</label>
                </div>

                <div class="d-flex mx-4 ps-1">
                    <div class="form-check ps-0 mb-0 me-4">
                        <input class="form-check-input" type="radio" name="info" id="info" value="1" checked>
                        <label class="form-check-label info" for="info" style="margin: 2px 0 0 -4px;">
                            Terdaftar
                        </label>
                    </div>
                    <div class="form-check ps-0 mb-0 ms-4">
                        <input class="form-check-input" type="radio" name="info" id="info2" value="0">
                        <label class="form-check-label info2" for="info2" style="margin: 2px 0 0 -4px;">
                            Belum Terdaftar
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 12px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-warning" id="btn-update"
                onclick="editUser(event)">Update</button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    function toggleNISNField() {
    const role = document.getElementById("role").value;
    const nisnGroup = document.getElementById("nisn-group");
    const nisnInput = document.getElementById("nisn");

    if (role == "3") {
        nisnGroup.style.display = "block";
        nisnInput.setAttribute("required", "required");
    } else {
        nisnGroup.style.display = "none";
        nisnInput.removeAttribute("required");
        nisnInput.value = "";
    }
}

// Panggil saat halaman siap
document.addEventListener("DOMContentLoaded", toggleNISNField);

// Jalankan juga saat role berubah
document.getElementById("role").addEventListener("change", toggleNISNField);

// Jalankan ulang tiap kali modal dibuka
$('#myModal').on('shown.bs.modal', function () {
    toggleNISNField();
});

</script>
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