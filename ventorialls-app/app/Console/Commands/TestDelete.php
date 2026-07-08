<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $request = \Illuminate\Http\Request::create('/admin/transaksi/bulk-delete', 'POST', ['ids' => [2], 'pin' => '447747']);
        $controller = new \App\Http\Controllers\TransaksiController();
        try {
            $response = $controller->bulkDestroy($request);
            $this->info("Response Content: " . $response->getContent());
        } catch (\Exception $e) {
            $this->error("ERROR: " . $e->getMessage());
            $this->error("TRACE: " . $e->getTraceAsString());
        }
    }
}
