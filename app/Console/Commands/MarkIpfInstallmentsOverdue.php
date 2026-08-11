<?php

namespace App\Console\Commands;

use App\Models\IpfInstallment;
use Illuminate\Console\Command;

class MarkIpfInstallmentsOverdue extends Command
{
    protected $signature = 'ipf:mark-overdue';

    protected $description = 'Mark pending IPF installments past their due date as overdue, across all accounts.';

    public function handle(): int
    {
        $count = IpfInstallment::query()->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} installment(s) as overdue.");

        return self::SUCCESS;
    }
}