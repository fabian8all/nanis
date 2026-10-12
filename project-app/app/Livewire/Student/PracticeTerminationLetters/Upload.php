<?php

namespace App\Livewire\Student\PracticeTerminationLetters;

use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeTerminationLetter;
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

    public int $practiceRequestId;

    public ?TemporaryUploadedFile $letterFile = null;

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->authorizeStudent();
        $this->practiceRequestId = $practiceRequest->id;
        $this->practiceRequest();
    }

    public function submit(): void
    {
        $this->authorizeStudent();

        $data = $this->validate([
            'letterFile' => ['required', 'file', 'max:20480', 'mimes:pdf,jpg,jpeg,png'],
        ], [
            'letterFile.max' => 'La carta debe pesar como máximo 20 MB.',
            'letterFile.mimes' => 'El archivo debe ser PDF, JPG o PNG.',
        ]);

        $request = $this->practiceRequest();
        $mimeType = $data['letterFile']->getMimeType();
        $extension = match ($mimeType) {
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => throw new RuntimeException('El tipo real del archivo no es compatible.'),
        };
        $path = $data['letterFile']->storeAs(
            "practice-termination-letters/{$request->id}",
            Str::uuid().'.'.$extension,
            'local',
        );

        if ($path === false) {
            throw new RuntimeException('No se pudo guardar la carta de término en almacenamiento privado.');
        }

        try {
            $oldPath = DB::transaction(function () use ($data, $mimeType, $path, $request): ?string {
                $lockedRequest = PracticeRequest::query()->lockForUpdate()->findOrFail($request->id);
                if (
                    $lockedRequest->user_id !== auth()->id()
                    || $lockedRequest->status !== PracticeRequestStatus::Approved
                ) {
                    abort(404);
                }

                $letter = PracticeTerminationLetter::query()
                    ->where('practice_request_id', $lockedRequest->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    $letter?->status === PracticeTerminationLetterStatus::PendingReview
                    || $letter?->status === PracticeTerminationLetterStatus::Accepted
                ) {
                    throw ValidationException::withMessages([
                        'letterFile' => 'La carta no está disponible para una nueva carga.',
                    ]);
                }

                $oldPath = $letter?->file_path;
                $attributes = [
                    'file_path' => $path,
                    'original_name' => Str::limit($data['letterFile']->getClientOriginalName(), 255, ''),
                    'mime_type' => $mimeType,
                    'file_size' => $data['letterFile']->getSize(),
                    'status' => PracticeTerminationLetterStatus::PendingReview,
                    'review_comment' => null,
                    'reviewed_by' => null,
                    'submitted_at' => now(),
                    'reviewed_at' => null,
                ];

                if ($letter) {
                    $letter->update($attributes);
                } else {
                    $lockedRequest->terminationLetter()->create($attributes);
                }

                return $oldPath;
            });
        } catch (Throwable $exception) {
            if (! Storage::disk('local')->delete($path)) {
                throw new RuntimeException(
                    'Falló el registro de la carta y no se pudo retirar el archivo temporal.',
                    previous: $exception,
                );
            }

            throw $exception;
        }

        if (
            $oldPath !== null
            && $oldPath !== $path
            && Storage::disk('local')->exists($oldPath)
            && ! Storage::disk('local')->delete($oldPath)
        ) {
            throw new RuntimeException('La carta nueva se guardó, pero no se pudo retirar el archivo reemplazado.');
        }

        session()->flash('status', 'La carta se envió a Coordinación para revisión.');
        $this->redirectRoute('alumno.practice-termination-letters.upload', $request);
    }

    public function render(): View
    {
        $this->authorizeStudent();
        $request = $this->practiceRequest();

        return view('livewire.student.practice-termination-letters.upload', [
            'practiceRequest' => $request,
            'letter' => $request->terminationLetter,
        ])->layout('layouts.app', ['title' => 'Carta de término']);
    }

    private function practiceRequest(): PracticeRequest
    {
        return PracticeRequest::query()
            ->whereKey($this->practiceRequestId)
            ->where('user_id', auth()->id())
            ->where('status', PracticeRequestStatus::Approved->value)
            ->with('terminationLetter')
            ->firstOrFail();
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
