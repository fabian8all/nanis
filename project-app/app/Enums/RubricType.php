<?php

namespace App\Enums;

enum RubricType: string
{
    case Supervisor = 'supervisor';
    case Tutor = 'tutor';

    public function label(): string
    {
        return $this === self::Supervisor ? 'Supervisor' : 'Tutor';
    }
}
