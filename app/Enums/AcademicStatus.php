<?php

namespace App\Enums;

enum AcademicStatus: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';

    public static function options(): array
    {
        return [
            self::PUBLISHED->value => 'Published',
            self::DRAFT->value => 'Draft',
            self::ARCHIVED->value => 'Archived',
        ];
    }
}
