<?php

use App\Jobs\FetchDataJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new FetchDataJob)->cron('10 * * * * *');
