<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;

class ExpireTickets extends Command
{
    protected $signature = 'tickets:expire';
    protected $description = 'Expira los tickets reservados cuyo tiempo ha terminado';

    public function handle()
    {
        $now = Carbon::now();
        
        $expiredTickets = Ticket::where('estado', 'reservado')
                               ->where('reservado_hasta', '<', $now)
                               ->get();
        
        $count = 0;
        
        foreach ($expiredTickets as $ticket) {
            $ticket->update(['estado' => 'disponible', 'reservado_hasta' => null]);
            $count++;
        }
        
        $this->info("Se han liberado $count tickets reservados cuyo tiempo expiró.");
        
        return Command::SUCCESS;
    }
}