<?php

namespace App\Http\Controllers\Frontend\Api;


use App\Models\Event;
use App\Models\Notice;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Models\NoticeCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\EventTypeResource;
use Illuminate\Support\Facades\Validator;

class HomeApiController extends Controller
{
    public function siteInformations()
    {
        $settings = DB::table('settings')->first();

        return response()->json([
            'success' => true,
            'settings' => [
                'website_name'       => $settings->website_name,
                'site_title'         => $settings->site_title,
                'site_motto'         => $settings->site_motto,
                'footer_description' => $settings->footer_description,

                'branding' => [
                    'logo_white' => $settings->site_logo_white,
                    'logo_black' => $settings->site_logo_black,
                    'favicon'    => $settings->site_favicon,
                    'theme_color' => $settings->theme_color,
                    'dark_mode'  => (bool) $settings->dark_mode,
                ],

                'contact' => [
                    'primary_email'   => $settings->primary_email,
                    'info_email'      => $settings->info_email,
                    'primary_phone'   => $settings->primary_phone,
                    'addresses'       => json_decode($settings->addresses, true),
                ],

                'seo' => [
                    'site_url'        => $settings->site_url,
                    'meta_title'      => $settings->meta_title,
                    'meta_keyword'    => $settings->meta_keyword,
                    'meta_tags'       => $settings->meta_tags,
                    'meta_description' => $settings->meta_description,
                    'og_image'        => $settings->og_image,
                    'og_title'        => $settings->og_title,
                    'og_description'  => $settings->og_description,
                ],

                'social_links'      => json_decode($settings->social_links, true),
                'business_hours'    => json_decode($settings->business_hours, true),
                'custom_settings'   => json_decode($settings->custom_settings, true),
            ],
        ]);
    }

    public function noticeCategories()
    {
        $categories = NoticeCategory::with([
            'notices' => function ($q) {
                $q->select('id', 'category_id', 'title', 'slug', 'publish_date', 'attachments');
            }
        ])
            ->select('id', 'name', 'slug')
            ->where('status', 'active')
            ->get();

        // Extract first attachment from JSON array
        $categories->each(function ($category) {
            $category->notices->each(function ($notice) {
                $attachments = json_decode($notice->attachments, true);
                $notice->first_attachment = $attachments[0] ?? null;
            });
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }


    public function allNotices(Request $request)
    {
        $query = Notice::select(
            'id',
            'title',
            'slug',
            'type',
            'publish_date',
            'category_id'
        )
            ->with('noticeCategory:id,name,slug')
            ->where('status', 'published')
            ->orderBy('publish_date', 'desc');

        // Filter by noticeCategory
        if ($request->has('noticeCategory')) {
            $query->whereHas('noticeCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by notice type (Academic, Tender, Research, General)
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20)
        ]);
    }



    public function contactStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:150',
            'email'      => 'required|email|max:150',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'nullable|string',
            'message'    => 'nullable|string',
            'ip_address' => 'nullable|ip|max:100',
        ], [
            'name.required'  => 'The name field is required.',
            'name.string'    => 'The name must be a string.',
            'name.max'       => 'The name may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.max'      => 'The email may not be greater than :max characters.',
            'phone.string'   => 'The phone must be a string.',
            'phone.max'      => 'The phone may not be greater than :max characters.',
            'phone.regex'    => 'The phone field must contain only numeric characters and must be a proper number.',
            'subject.string' => 'The subject must be a string.',
            'message.string' => 'The message must be a string.',
            'ip_address.ip'  => 'Please enter a valid IP address.',
            'ip_address.max' => 'The IP address may not be greater than :max characters.',
        ]);

        if ($request->filled('phone')) {
            $validator->sometimes('phone', 'regex:/^[0-9]+$/i', function ($input) {
                return $input->phone;
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $typePrefix = 'MSG';
        $today      = date('dmy');
        $lastCode   = Contact::where('code', 'like', $typePrefix . '-' . $today . '%')
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastCode ? (int) explode('-', $lastCode->code)[2] + 1 : 1;
        $code      = $typePrefix . '-' . $today . '-' . $newNumber;

        $contact = Contact::create([
            'code'       => $code,
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'ip_address' => $request->ip(),
            'status'     => 'pending',
            'call'       => $request->call,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thank You. We have received your message. We will contact you very soon.',
            'data'    => $contact
        ], 201);
    }
}
