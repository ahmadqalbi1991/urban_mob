<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Slot;
use App\PrefferedDays;
use App\Card;

class SlotController extends BaseController
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
    
        $date = $request->input('date');
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
    
        // Fetch all slots
        $allSlots = Slot::all();
    
        // Fetch booked slots for the given date
        $bookedSlots = Card::where('date', $date)
                            ->where('status', '!=', 'Canceled')
                            ->where('service_id', $request->service_id)
                            ->pluck('slot_id')->toArray();
    
        $availableSlots = [];
        foreach ($allSlots as $slot) {
            // Convert both times to timestamps for comparison
            $slotStartTime = strtotime($slot->check_in);
            $currentTimeTimestamp = strtotime($currentTime);
    
            // Disable past time slots only if the date is today
            $isPastSlot = false;
            if ($date == $today && $slotStartTime < $currentTimeTimestamp) {
                $isPastSlot = true;
            }
    
            $availableSlots[] = [
                'slot_id' => $slot->id,
                'slot_name' => $slot->name,
                'available' => ($isPastSlot) ? false : !in_array($slot->id, $bookedSlots), 
                'is_past_slot' => $isPastSlot 
            ];
        }
    
        return $this->sendResponse($availableSlots, 'Slot Info');
    }

    public function preffered_days()
    {
        $data = PrefferedDays::all();
        return $this->sendResponse($data, 'Days List');
    }
}
