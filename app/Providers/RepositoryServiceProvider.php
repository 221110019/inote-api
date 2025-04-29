<?php

namespace App\Providers;

use App\Interfaces\NotesRepositoryInterface;
use App\Repositories\NotesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(NotesRepositoryInterface::class, NotesRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
