<?php
namespace App\DTOs\Dashboard;

class DashboardDTO
{
    public function __construct(
        public array $kpis,
        public array $charts
    ) {}
}
