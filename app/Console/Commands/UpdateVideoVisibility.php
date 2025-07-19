<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class UpdateVideoVisibility extends Command
{
    protected $signature = 'videos:update-visibility';
    protected $description = 'Update video visibility based on publish_at timestamp';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //
    }
}
