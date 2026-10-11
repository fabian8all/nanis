<?php

namespace App\Enums;

enum UserRole: string
{
    case Coordinator = 'coordinator';
    case Student = 'student';
    case Tutor = 'tutor';
    case Supervisor = 'supervisor';
}
