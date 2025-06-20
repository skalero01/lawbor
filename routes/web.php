<?php

use App\Livewire;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', Livewire\Web\Home::class)->name('home');

/*
|--------------------------------------------------------------------------
| Logged Routes
|--------------------------------------------------------------------------*/

Route::middleware(['auth', 'security'])->group(function () {
	// Admin panel
	Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
		Route::get('/', Livewire\Admin\Dashboard::class)->name('dashboard');
		Route::get('/documents', Livewire\Admin\Documents\Index::class)->name('documents');
		Route::get('/documents/ocr/{document}', Livewire\Admin\Documents\DocumentOcrView::class)->name('documents.ocr');
		Route::get('/documents/anonymized/{document}', Livewire\Admin\Documents\DocumentAnonymizedView::class)->name('documents.anonymized');
		Route::get('/documents/ai-analysis/{document}', Livewire\Admin\Documents\DocumentAiAnalysisView::class)->name('documents.ai-analysis');
		Route::get('/documents/rehydrated/{document}', Livewire\Admin\Documents\DocumentRehydratedView::class)->name('documents.rehydrated');
		
		Route::get('/ai', Livewire\Admin\Ai\Index::class)->name('ai.index');
		Route::get('/ai/providers/create', Livewire\Admin\Ai\ProviderForm::class)->name('ai.providers.create');
		Route::get('/ai/providers/{provider?}/edit', Livewire\Admin\Ai\ProviderForm::class)->name('ai.providers.edit');
		Route::get('/ai/prompts/create', Livewire\Admin\Ai\PromptForm::class)->name('ai.prompts.create');
		Route::get('/ai/prompts/{prompt?}/edit', Livewire\Admin\Ai\PromptForm::class)->name('ai.prompts.edit');
		Route::get('/ai/configurations/create', Livewire\Admin\Ai\ConfigurationForm::class)->name('ai.configurations.create');
		Route::get('/ai/configurations/{configuration?}/edit', Livewire\Admin\Ai\ConfigurationForm::class)->name('ai.configurations.edit');

		Route::front('User');
		Route::front('Role');
		Route::get('dev', Livewire\Admin\DevZone::class)->name('dev');
	});

	// App panel
	Route::prefix('app')->name('app.')->group(function () {
		Route::get('/', Livewire\App\Dashboard::class)->name('dashboard');
	});

	// Auth panel
	Route::prefix('auth')->name('auth.')->group(function () {
		Route::get('/', Livewire\Auth\MyProfile::class)->name('profile');
		Route::get('/notifications', Livewire\Auth\Notifications\Index::class)->name('notifications.center');
	});
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/offline', function () {
	return view('vendor/laravelpwa/offline');
});
