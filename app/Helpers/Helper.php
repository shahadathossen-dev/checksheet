<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Helper
{
    /**
     * Generates formated id with given value
     *
     * @param int
     * @return string
     */
    public function generateReadableId($value, $prefix = null, $length = 7)
    {
        return $prefix . \str_pad($value, $length, "0", STR_PAD_LEFT);
    }


    /**
     * Generate a formated purchase order number
     *
     * @return string
     */
    public function generateReadableIdWithDate($last, $prefix, $length = 5)
    {
        //Set initial value
        $value = 1;

        //Set the prefix with date
        $finalPrefix = $prefix . Carbon::now()->format('ym');

        //Parse the last value
        $lastValue = intval(substr($last, strlen($finalPrefix), $length));

        //Parse the last month
        $lastMonth = intval(substr($last, (strlen($prefix) + 2), 2));

        //Set the value
        if ($lastMonth == Carbon::now()->month) {
            $value = $lastValue + 1;
        }

        return $this->generateReadableId($value, $finalPrefix, $length);  // POF200700001
    }

    /**
     * Get all date between two dates
     *
     * @return array
     */
    public function getAllDates($start, $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $dateRange = CarbonPeriod::create($start, $end);

        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * Get the user available permission
     *
     * @return array
     */
    public function availablePermissions()
    {
        $result = [];
        $permissions = Cache::get(config('permission.cache.key'), function () {
            return Permission::all();
        });
        $permissionsCollection = collect($permissions['permissions'] ?? $permissions);
        $authUser =  Auth::user();
        if ($authUser && $authUser->id) {
            foreach ($permissionsCollection as $permission) {
                $name = $permission['c'] ?? $permission['name'];
                $authUserModel = User::findOrFail($authUser->id);
                $result[Str::camel($name)] = $authUserModel->hasPermissionTo($name);
            };
        }

        return $result;
    }

    /**
     * Calculate percentage
     *
     * @return double
     */
    public function calculatePercentage($amount, $total)
    {
        return round(($amount / $total) * 100, 2);
    }

    /**
     * Calculate percentage value
     *
     * @return double
     */
    public function calculatePercentageValue($percent, $total)
    {
        return round(($percent / 100) * $total, 2);
    }

    /**
     * Get currency code from the settings
     *
     * @return string
     */
    public function getCountryCode()
    {
        // return CacheHelper::currency() ? CacheHelper::currency()->data['code'] : "USD";
    }

    /**
     * Converts a given array of attribute keys to the casing required by CamelCaseModel.
     *
     * @param $attributes
     * @return array
     */
    public function toSnakeCase($attributes)
    {
        $convertedAttributes = [];

        foreach ($attributes as $key => $value) {
            $convertedAttributes[$this->getSnakeKey($key)] = $value;
        }

        return $convertedAttributes;
    }

    /**
     * If the field names need to be converted so that they can be accessed by camelCase, then we can do that here.
     *
     * @param $key
     * @return string
     */
    protected function getSnakeKey($key)
    {
        return Str::snake($key);
    }
}
