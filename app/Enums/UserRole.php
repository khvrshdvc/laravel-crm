<?php

namespace App\Enums;

// defines the roles available in the system

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Employee = 'employee'; 
}