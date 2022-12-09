<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\ResizeImage;

class ResizeImageProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * uploaded file name
     *
     * @var string
     */
    public $file_name;

    /**
     * uploaded file media type
     *
     * @var string
     */
    public $media_type;

    /**
     * Create a new job instance.
     *
     * @param string $file_name
     * @param string $media_type
     * @return void
     */
    public function __construct(string $file_name, string $media_type)
    {
        $this->onQueue('resize-image-queue');
        $this->file_name = $file_name;
        $this->media_type = $media_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resizeImage = new ResizeImage();
        $resizeImage->resize($this->file_name, $this->media_type);
    }
}
