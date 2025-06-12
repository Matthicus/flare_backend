<?php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Route::get('/run-migrations', function () {
//     Artisan::call('migrate', ['--force' => true]);
//     return '✅ Migrations complete!';
// });

// Route::get('/db-tables', function () {
//     $tables = \DB::select('SELECT tablename FROM pg_tables WHERE schemaname = current_schema()');
//     return response()->json($tables);
// });


require __DIR__.'/auth.php';
