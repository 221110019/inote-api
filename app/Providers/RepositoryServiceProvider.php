<?php

namespace App\Providers;

use App\Interfaces\NotesRepositoryInterface;
use App\Repositories\NotesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NotesRepositoryInterface::class, NotesRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
