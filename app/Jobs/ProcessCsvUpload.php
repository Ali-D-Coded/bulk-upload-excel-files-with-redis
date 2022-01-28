<?php

namespace App\Jobs;

use App\Models\Cards;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessCsvUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $file)
    {
       $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('upload-csv')->allow(1)->every(20)->then(function(){
            //job logic
            dump('processing this file: -----',$this->file);

            $data = array_map('str_getcsv', file($this->file));

            foreach($data as $row){
                Cards::updateOrCreate(
                    ['operator' => $row[0]],
                    ['card-number' => $row[1]]);
            }
            dump('done processing this file: -----',$this->file);
           unlink($this->file);
        }, function (){
            //could not obtail lock..

            return $this->release(10);
        });



    }
}
