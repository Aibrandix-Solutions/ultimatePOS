<?php

namespace App\Listeners;

use App\Events\SellCreatedOrModified;
use App\Damage;
use Illuminate\Contracts\Events\Dispatcher;

class DecreaseDamageOnSell
{
    /**
     * Handle the event.
     *
     * @param  SellCreatedOrModified  $event
     * @return void
     */
    public function handle(SellCreatedOrModified $event)
    {
        $transaction = $event->transaction;

        if (! $transaction || $transaction->type !== 'sell') {
            return;
        }

        $location_id = $transaction->location_id;

        // Ensure sell lines are loaded
        $sell_lines = $transaction->sell_lines()->get();

        foreach ($sell_lines as $line) {
            $remaining = (float) $line->quantity;
            if ($remaining <= 0) {
                continue;
            }

            $query = Damage::where('product_id', $line->product_id)
                ->where(function ($q) use ($line) {
                    if (!empty($line->variation_id)) {
                        $q->where('variation_id', $line->variation_id);
                    }
                })
                ->where('quantity', '>', 0);

            if (! empty($location_id)) {
                $query->where('location_id', $location_id);
            }

            $damages = $query->orderBy('created_at', 'asc')->get();

            foreach ($damages as $damage) {
                if ($remaining <= 0) break;

                $to_deduct = min($remaining, (float) $damage->quantity);
                $damage->quantity = (float) $damage->quantity - $to_deduct;
                $damage->save();

                $remaining -= $to_deduct;
            }
        }
    }
}
