<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/show', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}/update', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}/destroy', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::post('/attachments/{project}/add', [AttachmentController::class, 'store'])->name('attachments.add');
    Route::put('/attachments/{project}/sort', [AttachmentController::class, 'sort'])->name('attachments.sort');
    Route::delete('/attachments/{project}/destroy', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    Route::post('/attachments/{project}/epub', [AttachmentController::class, 'epub'])->name('attachments.epub');

    Route::get('/books/{project}/{book}/dl', [AttachmentController::class, 'download'])->name('books.download');
    Route::delete('/books/{project}/rm', [AttachmentController::class, 'destroyEpub'])->name('books.delete');
});

require __DIR__.'/auth.php';
