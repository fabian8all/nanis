<?php

namespace Tests\Unit;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use PHPUnit\Framework\TestCase;

class VersionStatusLabelTest extends TestCase
{
    public function test_acceptance_statuses_have_spanish_labels(): void
    {
        $this->assertSame('Pendiente de revisión', PracticeAcceptanceStatus::PendingReview->label());
        $this->assertSame('Devuelta', PracticeAcceptanceStatus::Returned->label());
        $this->assertSame('Aprobada', PracticeAcceptanceStatus::Accepted->label());
    }

    public function test_plan_statuses_have_spanish_labels(): void
    {
        $this->assertSame('Pendiente de revisión por el Supervisor', PracticePlanStatus::PendingSupervisorReview->label());
        $this->assertSame('Pendiente de revisión por Coordinación', PracticePlanStatus::PendingCoordinatorReview->label());
        $this->assertSame('Devuelta por el Supervisor', PracticePlanStatus::ReturnedBySupervisor->label());
        $this->assertSame('Devuelta por Coordinación', PracticePlanStatus::ReturnedByCoordinator->label());
        $this->assertSame('Aprobada', PracticePlanStatus::Approved->label());
    }

    public function test_report_statuses_have_spanish_labels(): void
    {
        $this->assertSame('Borrador', PracticeReportStatus::Draft->label());
        $this->assertSame('Pendiente de revisión por el Tutor', PracticeReportStatus::PendingTutorReview->label());
        $this->assertSame('Devuelta', PracticeReportStatus::Returned->label());
        $this->assertSame('Aprobada', PracticeReportStatus::Approved->label());
    }
}
