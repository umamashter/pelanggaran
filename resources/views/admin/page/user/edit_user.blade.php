<div class="modal fade us-modal" id="usModalEdit" tabindex="-1" aria-labelledby="usModalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="us-modal-hero" style="background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 18px 40px -12px rgba(217,119,6,.4);">
                <div class="us-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="us-modal-badge"><i class="fas fa-edit"></i></span>
                        <div>
                            <h4 class="us-modal-title" id="usModalEditLabel">Edit User</h4>
                            <p class="us-modal-subtitle">Perbarui profil akun user. Role tidak dapat diubah di sini.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="us-modal-meta">
                    <div class="us-modal-meta-item"><div class="k">Role</div><div class="v" id="usEditRoleMeta">-</div></div>
                    <div class="us-modal-meta-item"><div class="k">Status</div><div class="v" id="usEditStatusMeta">-</div></div>
                    <div class="us-modal-meta-item"><div class="k">ID</div><div class="v" id="usEditIdMeta">-</div></div>
                </div>
            </div>
            <form id="usFormEdit" novalidate>
                @csrf
                <input type="hidden" name="user_id" id="usEditUserId">
                <div class="modal-body">
                    <div class="us-identity mb-3">
                        <span class="us-avatar c0" id="usEditAvatar">?</span>
                        <div>
                            <div class="us-identity-name" id="usEditNameHeader">-</div>
                            <div class="us-identity-meta"><i class="fas fa-at"></i> <span id="usEditUsername">-</span></div>
                        </div>
                    </div>
                    <div class="us-form-2col">
                        <div class="us-form-grid" style="margin-top:0;">
                            <div class="us-float" id="usEditNameWrap">
                                <input type="text" id="usEditName" name="name" value="" placeholder=" " autocomplete="off">
                                <label for="usEditName">Nama</label>
                                <button type="button" class="us-field-undo" data-for="name" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                            <div class="us-float" id="usEditEmailWrap">
                                <input type="text" id="usEditEmail" name="email" value="" placeholder=" " autocomplete="off">
                                <label for="usEditEmail">Email</label>
                                <button type="button" class="us-field-undo" data-for="email" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                            <div class="us-float" id="usEditNisnWrap" style="display:none;">
                                <input type="text" id="usEditNisn" name="nisn" value="" placeholder=" " autocomplete="off" maxlength="10">
                                <label for="usEditNisn">NISN</label>
                                <button type="button" class="us-field-undo" data-for="nisn" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                            <div class="us-feedback" id="usEditFeedback"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>
                        <div>
                            <label class="abm-field-label"><i class="fas fa-user-check"></i>Status Terdaftar</label>
                            <div class="us-status-grid">
                                <button type="button" class="us-status-opt" data-info="1" id="usInfoOpt1">
                                    <span class="dot"><i class="fas fa-check"></i></span>
                                    <span><span class="k">Terdaftar</span><div class="d">Akun aktif dan dapat login</div></span>
                                </button>
                                <button type="button" class="us-status-opt" data-info="0" id="usInfoOpt0">
                                    <span class="dot"><i class="fas fa-user-clock"></i></span>
                                    <span><span class="k">Belum Terdaftar</span><div class="d">Akun menunggu pendaftaran</div></span>
                                </button>
                            </div>
                            <div class="us-hintbox mt-3">
                                <i class="fas fa-info-circle"></i>
                                <div>Password diatur ulang lewat menu khusus <b>Ubah Password</b>. Status siswa aktif hanya dapat diubah saat siswa mendaftar sendiri.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="us-modal-footer-note">
                        <i class="fas fa-sync-alt me-1"></i>Perubahan ditandai otomatis.
                        <button type="button" id="usEditReset" class="us-tool-btn" style="min-height:30px;padding:0 10px;font-size:11.5px;"><i class="fas fa-undo"></i> Reset</button>
                    </div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid us-ripple" id="usBtnEdit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
