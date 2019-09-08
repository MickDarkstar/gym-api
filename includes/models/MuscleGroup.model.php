<?php
// Custom "Enum", keep synced with table MuscleGroup
final class MuscleGroup
{
    public const AbsAndCore = 1;
    public const Arms       = 2;
    public const Back       = 3;
    public const Chest      = 4;
    public const Legs       = 5;
    public const Shoulders  = 6;

    private const MUSCLEGROUP_OPTIONS = array(
        self::AbsAndCore    => 'Abs & Core',
        self::Arms          => 'Arms',
        self::Back          => 'Back',
        self::Chest         => 'Chest',
        self::Legs          => 'Legs',
        self::Shoulders     => 'Shoulders'
    );

    public function All()
    {
        return self::MUSCLEGROUP_OPTIONS;
    }
}
