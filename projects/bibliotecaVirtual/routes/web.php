<?php

use App\Models\ComprasView;
use Illuminate\Support\Facades\Route;
use App\Exports\RelatorioAutoresLivrosAssuntosExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['middleware' => ['auth']], function () {
    Route::get('carrinho', function () {
        return view('carrinho');
    })->name('carrinho');

    Route::get('/export-relatorio', function () {
        $timestamp = now()->format('Ymd_His');  // Formato: AnoMesDia_HoraMinutoSegundo
        $fileName = 'relatorio_autores_livros_assuntos_' . $timestamp . '.xlsx';

        return Excel::download(new RelatorioAutoresLivrosAssuntosExport, $fileName);
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
