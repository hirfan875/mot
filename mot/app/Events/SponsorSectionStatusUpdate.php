<?php

namespace App\Events;

use App\Models\SponsorSection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SponsorSectionStatusUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The section instance.
     *
     * @var \App\Models\SponsorSection
     */
    public $section;

    /**
     * Create a new event instance.
     *
     * @param SponsorSection $section
     * @return void
     */
    public function __construct(SponsorSection $section)
    {
        $this->section = $section;
    }
}
