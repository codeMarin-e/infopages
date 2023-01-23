<?php

use App\Models\Infopage;
use App\Policies\InfopagePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::model('chInfopage', Infopage::class);
Gate::policy(Infopage::class, InfopagePolicy::class);

