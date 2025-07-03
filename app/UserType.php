<?php

namespace App;

enum UserType:string
{
      case ADMINISTRADOR = 'administrador';
    case NURSE = 'enfermeiro';
    case DOCTOR = 'medico';
    case MANAGER = 'gestor';
    case TECNICO ='tecnico';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::NURSE => 'Enfermeiro',
            self::DOCTOR => 'Médico',
            self::MANAGER => 'Gestor',
            self::TECNICO=>'Tecnico',
        };
    }

    public static function options(): array
    {
        return [
            self::ADMINISTRADOR->value => self::ADMINISTRADOR->label(),
            self::NURSE->value => self::NURSE->label(),
            self::DOCTOR->value => self::DOCTOR->label(),
            self::MANAGER->value => self::MANAGER->label(),
            self::TECNICO->VALUE =>self::TECNICO->label(),
        ];
    }
}
