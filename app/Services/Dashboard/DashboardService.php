<?php
namespace App\Services\Dashboard;

use App\DTOs\Dashboard\DashboardDTO;

class DashboardService
{
    public function __construct(
        private KpiService $kpiService,
        private ChartService $chartService
    ) {}

    public function build(): DashboardDTO
    {
        return new DashboardDTO(
            kpis: $this->kpiService->main(),
            charts: $this->chartService->main()
        );
    }
}
