<?php

namespace App\Providers;

use Exception;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default shared values
        View::share('setting', null);
        View::share('getOnlineVisitorCount', null);
        View::share('getTodayVisitorCount', null);
        // View::share('formattedHours', null);

        $setting = null;

        try {
            if (Schema::hasTable('settings')) {
                $setting = Setting::first();
                View::share('setting', $setting);
            }
        } catch (Exception $e) {
            // Optionally log the exception
        }

        // Locale & timezone
        Carbon::setLocale('en');
        date_default_timezone_set('Asia/Dhaka');

        /**
         * BUSINESS HOURS FORMATTING
         * -------------------------
         * Supports:
         *  - $setting->business_hours as JSON string (from seeder / DB)
         *  - $setting->business_hours as array (if casted in model)
         */
        // $businessHoursRaw = optional($setting)->business_hours;

        // if (is_string($businessHoursRaw)) {
        //     $businessHours = json_decode($businessHoursRaw, true) ?? [];
        // } elseif (is_array($businessHoursRaw)) {
        //     $businessHours = $businessHoursRaw;
        // } else {
        //     $businessHours = [];
        // }

        // // Define the day order and labels
        // $weekDays = [
        //     'saturday'  => 'Saturday',
        //     'sunday'    => 'Sunday',
        //     'monday'    => 'Monday',
        //     'tuesday'   => 'Tuesday',
        //     'wednesday' => 'Wednesday',
        //     'thursday'  => 'Thursday',
        //     'friday'    => 'Friday',
        // ];

        // $grouped      = [];
        // $currentGroup = null;

        // foreach ($weekDays as $dayKey => $dayLabel) {
        //     $dayData = $businessHours[$dayKey] ?? null;

        //     // Default to closed if no data
        //     if (!is_array($dayData)) {
        //         $hours = 'closed';
        //     } else {
        //         // If explicit closed flag is set
        //         if (!empty($dayData['closed'])) {
        //             $hours = 'closed';
        //         } else {
        //             $start = $dayData['start'] ?? null;
        //             $end   = $dayData['end'] ?? null;

        //             // If either is missing, treat as closed
        //             $hours = ($start && $end) ? $start . '-' . $end : 'closed';
        //         }
        //     }

        //     if ($currentGroup && $grouped[$currentGroup]['hours'] === $hours) {
        //         // Same hours as previous group, extend its days
        //         $grouped[$currentGroup]['days'][] = $dayLabel;
        //     } else {
        //         // Start a new group
        //         $currentGroup = uniqid('', true);
        //         $grouped[$currentGroup] = [
        //             'hours' => $hours,
        //             'days'  => [$dayLabel],
        //         ];
        //     }
        // }

        // $formattedHours = [];

        // foreach ($grouped as $group) {
        //     $days  = $group['days'];
        //     $hours = $group['hours'];

        //     // Normalize single vs multiple days display
        //     $dayRange = count($days) > 1
        //         ? $days[0] . '–' . end($days)
        //         : $days[0];

        //     if ($hours === 'closed') {
        //         $formattedHours[] = $dayRange . ': Closed';
        //     } else {
        //         [$start, $end] = explode('-', $hours);
        //         $formattedStart = Carbon::createFromTimeString($start)->format('gA'); // 09:00 -> 9AM
        //         $formattedEnd   = Carbon::createFromTimeString($end)->format('gA');   // 18:00 -> 6PM
        //         $formattedHours[] = $dayRange . ": {$formattedStart} – {$formattedEnd}";
        //     }
        // }

        try {
            if (Schema::hasTable('settings')) {
                View::share('setting', $setting);
                // View::share('formattedHours', $formattedHours);
            }
        } catch (Exception $e) {
            // Optionally log
        }

        Paginator::useBootstrap();
    }
}
