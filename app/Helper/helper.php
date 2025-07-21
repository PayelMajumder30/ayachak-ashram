<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\AcademicSession;
use Carbon\Carbon;
use App\Models\{DesignationPermission, StudentsMark};

//for create employee ID
if (!function_exists('generateEmployeeId')) {
    function generateEmployeeId()
    {
       // Include soft-deleted records using withTrashed()
        $lastId = Admin::withTrashed()
            ->where('user_id', 'LIKE', 'EMP%')
            ->orderByRaw("CAST(SUBSTRING(user_id, 4) AS UNSIGNED) DESC")
            ->value('user_id');

        if ($lastId) {
            $number = (int) substr($lastId, 3); // Extract number after 'EMP'
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }

        // Return in EMP0001 format
        return 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

//for create teacher ID
if (!function_exists('generateTeacherId')) {
    function generateTeacherId()
    {
        $lastId = Admin::withTrashed()
            ->where('user_id', 'LIKE', 'TEACH%')
            ->orderByRaw("CAST(SUBSTRING(user_id, 6) AS UNSIGNED) DESC") // Start from 6th char (after 'TEACH')
            ->value('user_id');

        if ($lastId) {
            $number = (int) substr($lastId, 5); // Extract numeric part
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }

        return 'TEACH' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}



if(!function_exists('hasPermissionByParent')){
    function hasPermissionByParent($parentName){
        // Ensure designation is loaded
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->designationData) {
            return false;
        }
        $permission_id = Permission::where('parent_name', $parentName)->value('id');
        if($permission_id){
            return DesignationPermission::where('permission_id', $permission_id)->where('designation_id', $user->designationData->id)->exists();
        }else{
            return false;
        }
    }
}

if(!function_exists('hasPermissionByChild')){
    function hasPermissionByChild($childName){
        // Ensure designation is loaded
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->designationData) {
            return false;
        }
        $permission_id = Permission::where('name', $childName)->value('id');
        if($permission_id){
            return DesignationPermission::where('permission_id', $permission_id)->where('designation_id', $user->designationData->id)->exists();
        }else{
            return false;
        }
    }
}


//for generate new session
if (!function_exists('createNewSession')) {
    function createNewSession() {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $sessionName = $currentYear . '-' . $nextYear;

        // Optional: define start and end date for academic year
        $startDate = Carbon::create($currentYear, 4, 1);  // e.g. April 1, current year
        $endDate = Carbon::create($nextYear, 3, 31);      // e.g. March 31, next year

        // Create or update the academic session
        $session = AcademicSession::updateOrCreate(
            ['session_name' => $sessionName],
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => 1, // or 0 depending on your logic
            ]
        );
        return $session;
    }
}
if (!function_exists('createNewExistingSession')) {
    function createNewExistingSession($value)
    {
        // Sanitize and parse the session string (e.g., '2027-2028')
        $value = trim($value);
        $years = explode('-', $value);

        // Validate the format
        if (count($years) !== 2 || !is_numeric($years[0]) || !is_numeric($years[1])) {
            return null; // Invalid format
        }

        $startYear = (int) $years[0];
        $endYear   = (int) $years[1];

        // Use full year format: '2027-2028'
        $sessionName = $startYear . '-' . $endYear;

        // Define session period (e.g., Apr 1 to Mar 31)
        $startDate = Carbon::create($startYear, 4, 1);
        $endDate   = Carbon::create($endYear, 3, 31);

        // Create or update the academic session
        $session = AcademicSession::updateOrCreate(
            ['session_name' => $sessionName],
            [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'is_active'  => 1, // Set to 0 if inactive by default
            ]
        );

        return $session;
    }
}