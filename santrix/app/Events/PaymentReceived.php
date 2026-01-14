<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Santri;

class PaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $santri;
    public $amount;
    public $monthName;
    public $year;
    public $arrearsInfo;
    public $type; // 'regular' or 'advance'

    /**
     * Create a new event instance.
     */
    public function __construct(Santri $santri, $amount, $monthName, $year, $arrearsInfo, $type = 'regular')
    {
        $this->santri = $santri;
        $this->amount = $amount;
        $this->monthName = $monthName;
        $this->year = $year;
        $this->arrearsInfo = $arrearsInfo;
        $this->type = $type;
    }
}
