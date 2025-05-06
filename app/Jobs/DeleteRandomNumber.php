<?php

namespace App\Jobs;

use App\Models\RandomNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteRandomNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $randomNumberId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($randomNumberId)
    {
        $this->randomNumberId = $randomNumberId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $randomNumber = RandomNumber::find($this->randomNumberId);
        if ($randomNumber) {
            $randomNumber->delete();
        }
    }
}
