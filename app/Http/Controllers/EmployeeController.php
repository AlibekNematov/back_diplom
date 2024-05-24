<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function getEmployeeList(): Collection
    {
        return Employee::all();
    }
}
