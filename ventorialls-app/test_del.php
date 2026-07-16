<?php

use App\Models\Transaction;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$t = Transaction::where('type', 'barang_keluar')->first();
if (! $t) {
    exit('No data');
} $req = Request::create('/workspaceinventory/mutasi/keluar/bulk-delete', 'POST', ['ids' => [$t->id]]);
$res = app()->call('App\Http\Controllers\MutasiController@bulkDestroyKeluar', ['request' => $req]);
echo json_encode($res->getData());
