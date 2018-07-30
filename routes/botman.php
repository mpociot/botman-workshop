<?php

use App\Http\Controllers\DeployController;

$botman = resolve('botman');

$botman->hears('deploy {projectname}', DeployController::class.'@deployProject');

$botman->hears('deploy', DeployController::class.'@deploy');