<?php
// Custom "Enum", keep synced with table MuscleGroup
final class MuscleGroupStaticRepository
{
    const AbsAndCore = 1;
    const Arms       = 2;
    const Back       = 3;
    const Chest      = 4;
    const Legs       = 5;
    const Shoulders  = 6;
    static private $array = array();

    static public function Get($id)
    {
        self::$array =         array(
            self::AbsAndCore    => 'Abs & Core',
            self::Arms          => 'Arms',
            self::Back          => 'Back',
            self::Chest         => 'Chest',
            self::Legs          => 'Legs',
            self::Shoulders     => 'Shoulders'
        );
        return new MuscleGroup($id, self::$array[$id]);
    }
}
