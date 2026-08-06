<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

view()->share('errors', new Illuminate\Support\ViewErrorBag);

function obj($props) { return (object) $props; }
function relObj($name, $id) { return $name ? obj(['nama_jenjang' => $name, 'id' => $id]) : null; }

$items = collect([
    obj(['id' => 1, 'nama_mapel' => 'Matematika', 'kode_mapel' => 'MAP001', 'jenjang_id' => 1, 'kurikulum_id' => 1, 'jenjang' => relObj('SD', 1), 'kurikulum' => relObj('Kurikulum Merdeka', 1), 'kelompok' => 'Umum', 'status' => 'Aktif']),
    obj(['id' => 2, 'nama_mapel' => 'Al-Quran Hadits', 'kode_mapel' => 'MAP002', 'jenjang_id' => 2, 'kurikulum_id' => 2, 'jenjang' => relObj('MI', 2), 'kurikulum' => relObj('Kurikulum 2013', 2), 'kelompok' => 'PAI', 'status' => 'Aktif']),
    obj(['id' => 3, 'nama_mapel' => 'Bahasa Arab', 'kode_mapel' => 'MAP003', 'jenjang_id' => 2, 'kurikulum_id' => 1, 'jenjang' => relObj('MI', 2), 'kurikulum' => relObj('Kurikulum Merdeka', 1), 'kelompok' => 'PAI', 'status' => 'Nonaktif']),
]);

$mapel = new Illuminate\Pagination\LengthAwarePaginator($items, 3, 10, 1, ['path' => '/']);
$kurikulums = collect([obj(['id' => 1, 'nama_kurikulum' => 'Kurikulum Merdeka']), obj(['id' => 2, 'nama_kurikulum' => 'Kurikulum 2013'])]);
$jenjangs = collect([obj(['id' => 1, 'nama_jenjang' => 'SD']), obj(['id' => 2, 'nama_jenjang' => 'MI'])]);
$perPage = 10;

$html = view('admin.matapelajaran.index', compact('mapel', 'kurikulums', 'jenjangs', 'perPage'))->render();
echo 'RENDER OK, length=' . strlen($html) . PHP_EOL;
echo 'insight: ' . (strpos($html, 'Sebaran per Jenjang') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'bulk bar: ' . (strpos($html, 'mplBulkBar') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'dropzone: ' . (strpos($html, 'mplDropzone') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'edit preview: ' . (strpos($html, 'Pratinjau Perubahan') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'wizard kelompok cards: ' . (strpos($html, 'mpl-kelompok-card') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'export modal: ' . (strpos($html, 'modalExport') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'breadcrumb: ' . (strpos($html, 'mpl-crumb') !== false ? 'yes' : 'no') . PHP_EOL;
