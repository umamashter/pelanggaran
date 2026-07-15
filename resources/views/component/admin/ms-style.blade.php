<style>
/* ============================================================
   MODERN STYLE (Master Siswa inspired)
   ============================================================ */

:root {
    --ms-primary: #16a34a;
    --ms-primary-dark: #15803d;
    --ms-primary-light: #dcfce7;
    --ms-bg: #f5f7fb;
    --ms-border: #e2e8f0;
    --ms-text: #1e293b;
    --ms-text-soft: #64748b;
}

.page-title-content {
    display: none !important;
}

.master-siswa-page {
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    margin-top: 22px;
}

/* ---- Header Icon ---- */
.header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    box-shadow: 0 4px 14px rgba(22,163,74,.3);
    flex-shrink: 0;
}

/* ---- Badge ---- */
.badge-modern {
    display: inline-flex;
    align-items: center;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.badge-ta {
    background: #f0fdf4;
    color: #16a34a;
}

/* ---- Header Buttons ---- */
.btn-header-ms {
    padding: 8px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    transition: all .25s;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    height: auto;
    text-decoration: none;
}

.btn-header-ms:hover {
    transform: translateY(-2px);
}

.btn-header-ms:active {
    transform: translateY(0);
}

.btn-header-ms.btn-tambah-ms {
    background: #fff;
    border: 1.5px solid var(--ms-border);
    color: #475569;
}

.btn-header-ms.btn-tambah-ms:hover {
    border-color: var(--ms-primary);
    color: var(--ms-primary);
    background: var(--ms-primary-light);
    box-shadow: 0 3px 8px rgba(0,0,0,.08);
}

.btn-header-ms.btn-simpan-ms {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    box-shadow: 0 2px 8px rgba(22,163,74,.25);
    cursor: pointer;
}

.btn-header-ms.btn-simpan-ms:hover {
    box-shadow: 0 6px 20px rgba(22,163,74,.35);
    color: #fff;
}

/* ---- Table Card ---- */
.table-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
}

.table-card .card-body {
    padding: 16px 20px 20px;
}

/* ---- Alert ---- */
.alert-modern-ms {
    border: none;
    border-radius: 12px;
    padding: 14px 20px;
    font-size: 14px;
    margin-bottom: 20px;
}

.alert-modern-ms.alert-success {
    background: #f0fdf4;
    color: #16a34a;
    border-left: 4px solid #16a34a;
}

.alert-modern-ms.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #dc2626;
}

/* ---- Table Styling ---- */
.table-ms {
    border-collapse: collapse;
    width: 100% !important;
    border: 1px solid var(--ms-border);
    border-radius: 12px;
    margin: 0 !important;
    table-layout: auto;
}

.table-ms thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 11px 14px;
    border-bottom: 2px solid var(--ms-border);
    white-space: nowrap;
    text-align: center;
}

.table-ms thead th:first-child {
    width: 60px;
}

.table-ms tbody td {
    padding: 10px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-ms tbody td:first-child {
    text-align: center;
    width: 60px;
}

.table-ms tbody tr:last-child td {
    border-bottom: none;
}

.table-ms tbody tr:hover td {
    background: #f8fafc;
}

.table-ms tbody tr:nth-child(even) td {
    background: #fafbfc;
}

.table-ms tbody tr:nth-child(even):hover td {
    background: #f1f5f9;
}

/* ---- Action Buttons ---- */
.action-group-ms {
    display: inline-flex;
    gap: 5px;
    flex-wrap: nowrap;
}

.action-group-ms .btn {
    width: 32px;
    height: 32px;
    border: none !important;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 12px;
    transition: all .25s;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
}

.action-group-ms .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0,0,0,.12);
}

.action-group-ms .btn-outline-info {
    background: #eff6ff;
    color: #2563eb;
}

.action-group-ms .btn-outline-info:hover {
    background: #2563eb;
    color: #fff;
    box-shadow: 0 3px 10px rgba(37,99,235,.3);
}

.action-group-ms .btn-outline-danger {
    background: #fef2f2;
    color: #dc2626;
}

.action-group-ms .btn-outline-danger:hover {
    background: #dc2626;
    color: #fff;
    box-shadow: 0 3px 10px rgba(220,38,38,.3);
}

.action-group-ms .btn-outline-warning {
    background: #fffbeb;
    color: #d97706;
}

.action-group-ms .btn-outline-warning:hover {
    background: #d97706;
    color: #fff;
    box-shadow: 0 3px 10px rgba(217,119,6,.3);
}

/* ---- DataTables Search ---- */
.dataTables_wrapper .dataTables_filter {
    float: none;
    text-align: right;
    margin-bottom: 8px;
}

.dataTables_wrapper .dataTables_filter label {
    position: relative;
    display: inline-flex;
    align-items: center;
    font-size: 0;
    line-height: 0;
    color: transparent;
}

.dataTables_wrapper .dataTables_filter label input {
    font-size: 14px;
    line-height: normal;
    color: var(--ms-text);
    height: 40px;
    border: 1.5px solid var(--ms-border);
    border-radius: 24px;
    padding: 0 16px 0 40px;
    width: 300px;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 14px center no-repeat;
    background-size: 16px;
    transition: all .25s;
    outline: none;
}

.dataTables_wrapper .dataTables_filter label input:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    background-color: #fff;
}

/* ---- DataTables Length ---- */
.dataTables_wrapper .dataTables_length {
    float: left;
    font-size: 14px;
    color: var(--ms-text-soft);
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid var(--ms-border);
    border-radius: 10px;
    padding: 4px 28px 4px 12px;
    font-size: 13px;
    background-color: #f8fafc;
    color: var(--ms-text);
    transition: all .2s;
    height: 38px;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    outline: none;
    background-color: #fff;
}

/* ---- DataTables Pagination ---- */
.dataTables_wrapper .dataTables_paginate {
    padding-top: 12px !important;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    float: none;
    text-align: right;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid var(--ms-border);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    background: #fff;
    cursor: pointer;
    transition: all .2s;
    min-width: 36px;
    text-align: center;
    display: inline-block;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: var(--ms-primary);
    color: var(--ms-primary);
    background: var(--ms-primary-light);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--ms-primary);
    border-color: var(--ms-primary);
    color: #fff;
    box-shadow: 0 2px 6px rgba(22,163,74,.25);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: .4;
    cursor: default;
    pointer-events: none;
    background: #f8fafc;
}

/* ---- DataTables Info ---- */
.dataTables_wrapper .dataTables_info {
    font-size: 13px;
    color: var(--ms-text-soft);
    padding-top: 16px !important;
    clear: both;
}

/* ---- Form Controls ---- */
.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid var(--ms-border);
    font-size: 14px;
    padding: 8px 14px;
    transition: all .2s;
    background-color: #f8fafc;
    color: var(--ms-text);
    height: 42px;
}

.form-control:focus, .form-select:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    background-color: #fff;
    outline: none;
}

textarea.form-control {
    height: auto;
}

/* ---- Input group with icon ---- */
.input-group-cu {
    position: relative;
}

.input-group-cu-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 4;
    font-size: 15px;
    pointer-events: none;
    transition: color .25s;
}

.input-group-cu:focus-within .input-group-cu-icon {
    color: var(--ms-primary);
}

.input-group-cu .form-control,
.input-group-cu .form-select {
    height: 46px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 0 16px 0 42px;
    font-size: 14px;
    color: var(--ms-text);
    background: #f8fafc;
    transition: all .25s;
    width: 100%;
    box-shadow: none;
}

.input-group-cu .form-control:focus,
.input-group-cu .form-select:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
    background-color: #fff;
}

.input-group-cu .form-select {
    padding-right: 36px;
    cursor: pointer;
    appearance: auto;
}

/* ---- Status Badge ---- */
.badge-status-ms {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.badge-status-ms.aktif {
    background: #dcfce7;
    color: #16a34a;
}

.badge-status-ms.nonaktif {
    background: #fef2f2;
    color: #dc2626;
}

/* ---- Responsive ---- */
@media (max-width: 992px) {
    .dataTables_wrapper .dataTables_filter label input {
        width: 220px;
    }
}

@media (max-width: 768px) {
    .table-card .card-body {
        padding: 12px 14px 16px;
    }

    .dataTables_wrapper .dataTables_filter {
        float: none;
        text-align: left;
        margin-bottom: 8px;
    }

    .dataTables_wrapper .dataTables_filter label input {
        width: 100%;
    }

    .dataTables_wrapper .dataTables_length {
        float: none;
        margin-bottom: 8px;
    }

    .dataTables_wrapper .dataTables_paginate {
        justify-content: center;
    }

    .dataTables_wrapper .dataTables_info {
        text-align: center;
    }

    .table-ms thead th {
        font-size: 11px;
        padding: 9px 8px;
    }

    .table-ms tbody td {
        padding: 8px;
        font-size: 12px;
    }

    .header-icon {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .table-card .card-body {
        padding: 8px 10px 12px;
    }

    .action-group-ms {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
    }

    .action-group-ms .btn {
        width: 100%;
        height: 28px;
        font-size: 11px;
    }

    .table-ms thead th {
        padding: 7px 5px;
        font-size: 10px;
    }

    .table-ms tbody td {
        padding: 7px 5px;
        font-size: 11px;
    }

    .dataTables_wrapper .dataTables_filter label input {
        width: 100%;
    }
}
</style>
