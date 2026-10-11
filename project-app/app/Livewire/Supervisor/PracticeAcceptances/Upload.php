<?php

namespace App\Livewire\Supervisor\PracticeAcceptances;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Models\PracticeLetter;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use RuntimeException;
use Throwable;

class Upload extends Component
{
    use WithFileUploads;

    public int $practiceLetterId;

    public ?TemporaryUploadedFile $acceptanceFile = null;

    public function mount(PracticeLetter $practiceLetter): void
    {
        $this->authorizeSupervisor();
        $this->practiceLetterId = $practiceLetter->id;
        $this->practiceRequest();
    }

    public function submit(): void
    {
        $this->authorizeSupervisor();
        $data = $this->validate([
            'acceptanceFile' => ['required', 'file', 'max:20480', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $request = $this->practiceRequest();
        $requestId = $request->id;
        $mimeType = $data['acceptanceFile']->getMimeType();
        $extension = match ($mimeType) {
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => throw new RuntimeException('El tipo real del archivo no es compatible.'),
        };
        $path = null;

        try {
            DB::transaction(function () use ($data, $extension, $mimeType, $requestId, &$path): void {
                $request = PracticeRequest::query()->lockForUpdate()->findOrFail($requestId);
                $this->assertUploadAllowed($request);
                $version = (int) $request->acceptanceSubmissions()->max('version') + 1;
                $path = $data['acceptanceFile']->storeAs(
                    "practice-acceptances/{$request->id}",
                    Str::uuid().'.'.$extension,
                    'local',
                );

                if ($path === false) {
                    throw new RuntimeException('No se pudo guardar la carta de aceptación en almacenamiento privado.');
                }

                $request->acceptanceSubmissions()->create([
                    'supervisor_id' => auth()->id(),
                    'version' => $version,
                    'file_path' => $path,
                    'file_extension' => $extension,
                    'mime_type' => $mimeType,
                    'file_size' => $data['acceptanceFile']->getSize(),
                    'status' => PracticeAcceptanceStatus::PendingReview,
                    'submitted_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            if (is_string($path)) {
                Storage::disk('local')->delete($path);
            }

            throw $exception;
        }

        $this->redirectRoute('supervisor.practice-letters.index');
    }

    public function render(): View
    {
        $this->authorizeSupervisor();
        $request = $this->practiceRequest();

        return view('livewire.supervisor.practice-acceptances.upload', [
            'letter' => $request->letter()
                ->with([
                    'practiceRequest' => fn ($query) => $query->select(['id', 'company_id', 'user_id']),
                    'practiceRequest.student:id,name',
                    'practiceRequest.company:id,name',
                ])
                ->firstOrFail(),
            'latestSubmission' => $request->latestAcceptanceSubmission,
        ])->layout('layouts.app', ['title' => 'Cargar carta de aceptación']);
    }

    private function practiceRequest(): PracticeRequest
    {
        return PracticeRequest::query()
            ->where('company_id', auth()->user()->company_id)
            ->where('status', PracticeRequestStatus::Approved->value)
            ->whereHas('letter', fn ($query) => $query->whereKey($this->practiceLetterId))
            ->whereDoesntHave('acceptanceSubmissions', fn ($query) => $query
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->with('latestAcceptanceSubmission')
            ->firstOrFail();
    }

    private function assertUploadAllowed(PracticeRequest $request): void
    {
        if ($request->company_id !== auth()->user()->company_id) {
            abort(404);
        }

        $latestSubmission = $request->latestAcceptanceSubmission()->first();
        if (
            $request->status !== PracticeRequestStatus::Approved
            || $latestSubmission?->status === PracticeAcceptanceStatus::Accepted
            || $latestSubmission?->status === PracticeAcceptanceStatus::PendingReview
        ) {
            throw ValidationException::withMessages([
                'acceptanceFile' => 'Esta carta no está disponible para una nueva carga.',
            ]);
        }
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at !== null, 403);
    }
}
