<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view dashboard')->only(['dashboard']);
    }

    public function dashboard()
    {
        $widgets = [];

        // helper: safe count
        $countTable = function (string $table) {
            if (!Schema::hasTable($table)) {
                return null;
            }
            return (int) DB::table($table)->count();
        };

        // helper: safe latest list
        $latestRows = function (string $table, array $columns, int $limit = 5) {
            if (!Schema::hasTable($table)) {
                return collect();
            }

            $hasCreatedAt = Schema::hasColumn($table, 'created_at');

            $q = DB::table($table)->select($columns);

            if ($hasCreatedAt) {
                $q->orderByDesc('created_at');
            } elseif (Schema::hasColumn($table, 'id')) {
                $q->orderByDesc('id');
            }

            return $q->limit($limit)->get();
        };

        // =========================
        // COUNTS (safe)
        // =========================
        $totalAcademicSites = $countTable('academic_sites');
        $totalAcademicPages = $countTable('academic_pages');
        $totalDepartments   = $countTable('academic_departments');
        $totalNotices       = $countTable('notices');
        $totalNews          = $countTable('news');
        $totalContacts      = $countTable('contacts');
        $totalSubscriptions = $countTable('subscriptions');

        // If you have users/staff/admins tables (optional)
        $totalAdmins = $countTable('admins');
        $totalUsers  = $countTable('users');
        $totalStaff  = $countTable('staff');
        $totalAcademicStaff  = $countTable('academic_staff_members');

        // =========================
        // WIDGETS (permission based)
        // =========================

        // Academic
        if (Auth::guard('admin')->user()->can('view academic sites')) {
            $widgets[] = [
                'title' => 'Faculties',
                'value' => $totalAcademicSites ?? 0,
                'desc'  => 'Total academic sites',
                'icon'  => 'fa-solid fa-sitemap fs-2',
                'bg'    => 'bg-light-primary',
                'text'  => 'text-primary',
                'route' => route('admin.academic.sites.index'),
            ];
        }

        if (Auth::guard('admin')->user()->can('view academic departments')) {
            $widgets[] = [
                'title' => 'Departments',
                'value' => $totalDepartments ?? 0,
                'desc'  => 'Total departments',
                'icon'  => 'fa-solid fa-building-columns fs-2',
                'bg'    => 'bg-light-warning',
                'text'  => 'text-warning',
                'route' => route('admin.academic.staff.index'),
            ];
        }


        if (Auth::guard('admin')->user()->can('view academic pages')) {
            $widgets[] = [
                'title' => 'Academic Pages',
                'value' => $totalAcademicPages ?? 0,
                'desc'  => 'Total custom pages',
                'icon'  => 'fa-solid fa-file-lines fs-2',
                'bg'    => 'bg-light-success',
                'text'  => 'text-success',
                'route' => route('admin.academic.pages.index'),
            ];
        }


        // Notice / News
        if (Auth::guard('admin')->user()->can('view notice')) {
            $widgets[] = [
                'title' => 'Notices',
                'value' => $totalNotices ?? 0,
                'desc'  => 'Published + drafts',
                'icon'  => 'fa-solid fa-bullhorn fs-2',
                'bg'    => 'bg-light-danger',
                'text'  => 'text-danger',
                'route' => route('admin.notice.index'),
            ];
        }

        if (Auth::guard('admin')->user()->can('view news')) {
            $widgets[] = [
                'title' => 'News',
                'value' => $totalNews ?? 0,
                'desc'  => 'All news items',
                'icon'  => 'fa-solid fa-newspaper fs-2',
                'bg'    => 'bg-light-info',
                'text'  => 'text-info',
                'route' => route('admin.news.index'),
            ];
        }

        // Contact / Subscription (optional in your project)
        // if (Auth::guard('admin')->user()->can('view contact')) {
        //     $widgets[] = [
        //         'title' => 'Contact Messages',
        //         'value' => $totalContacts ?? 0,
        //         'desc'  => 'Messages received',
        //         'icon'  => 'fa-solid fa-envelope fs-2',
        //         'bg'    => 'bg-light-primary',
        //         'text'  => 'text-primary',
        //         'route' => route('admin.contact.index'),
        //     ];
        // }

        // if (Auth::guard('admin')->user()->can('view subscription')) {
        //     $widgets[] = [
        //         'title' => 'Subscriptions',
        //         'value' => $totalSubscriptions ?? 0,
        //         'desc'  => 'Total subscribers',
        //         'icon'  => 'fa-solid fa-bell fs-2',
        //         'bg'    => 'bg-light-success',
        //         'text'  => 'text-success',
        //         'route' => route('admin.subscription.index'),
        //     ];
        // }

        // // Users / Staff (optional)
        // if (Auth::guard('admin')->user()->can('view user') && !is_null($totalUsers)) {
        //     $widgets[] = [
        //         'title' => 'Users',
        //         'value' => $totalUsers,
        //         'desc'  => 'Registered users',
        //         'icon'  => 'fa-solid fa-users fs-2',
        //         'bg'    => 'bg-light-warning',
        //         'text'  => 'text-warning',
        //         'route' => route('admin.user.index'),
        //     ];
        // }

        // if (Auth::guard('admin')->user()->can('view staff') && !is_null($totalStaff)) {
        //     $widgets[] = [
        //         'title' => 'Staff',
        //         'value' => $totalStaff,
        //         'desc'  => 'Total staff',
        //         'icon'  => 'fa-solid fa-user-tie fs-2',
        //         'bg'    => 'bg-light-danger',
        //         'text'  => 'text-danger',
        //         'route' => route('admin.staff.index'),
        //     ];
        // }
        if (Auth::guard('admin')->user()->can('view academic staff') && !is_null($totalAcademicStaff)) {
            $widgets[] = [
                'title' => 'Academic Staff',
                'value' => $totalAcademicStaff,
                'desc'  => 'Total academic staff',
                'icon'  => 'fa-solid fa-user-tie fs-2',
                'bg'    => 'bg-light-danger',
                'text'  => 'text-danger',
                'route' => route('admin.academic.staff.index'),
            ];
        }

        // =========================
        // RECENT ACTIVITIES (safe)
        // =========================
        $activities = collect();

        // latest notices
        $latestNotices = $latestRows('notices', ['id', 'title', 'created_at'], 5);
        foreach ($latestNotices as $n) {
            $activities->push([
                'time'  => $n->created_at ? date('H:i', strtotime($n->created_at)) : '--:--',
                'type'  => 'danger',
                'text'  => 'New notice: ' . ($n->title ?? ('#' . $n->id)),
            ]);
        }

        // latest news
        $latestNews = $latestRows('news', ['id', 'title', 'created_at'], 5);
        foreach ($latestNews as $n) {
            $activities->push([
                'time'  => $n->created_at ? date('H:i', strtotime($n->created_at)) : '--:--',
                'type'  => 'info',
                'text'  => 'News posted: ' . ($n->title ?? ('#' . $n->id)),
            ]);
        }

        // latest academic pages
        $latestPages = $latestRows('academic_pages', ['id', 'title', 'created_at'], 5);
        foreach ($latestPages as $p) {
            $activities->push([
                'time'  => $p->created_at ? date('H:i', strtotime($p->created_at)) : '--:--',
                'type'  => 'success',
                'text'  => 'Academic page updated: ' . ($p->title ?? ('#' . $p->id)),
            ]);
        }

        // sort activities by time if possible (created_at mixed), keep simple
        $activities = $activities->take(10)->values();

        return view('admin.dashboard', [
            'widgets'    => collect($widgets)->values(),
            'activities' => $activities,
        ]);
    }
}
