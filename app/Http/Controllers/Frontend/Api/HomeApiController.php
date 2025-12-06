<?php

namespace App\Http\Controllers\Frontend\Api;


use App\Models\News;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Admission;
use App\Models\EventType;
use App\Models\AdminGroup;
use App\Models\AdminOffice;
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
                    'site_logo_white'           => $settings->site_logo_white ? URL::to('storage/' . $settings->site_logo_white)       : null,
                    'site_logo_black'           => $settings->site_logo_black ? URL::to('storage/' . $settings->site_logo_black)       : null,
                    'site_favicon'              => $settings->site_favicon ? URL::to('storage/' . $settings->site_favicon)          : null,
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
                    'og_image'        => $settings->og_image ? URL::to('storage/' . $settings->og_image) : null,
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

        $categories->each(function ($category) {
            $category->notices->each(function ($notice) {
                // attachments is already array or null because of casts
                $attachments = $notice->attachments ?? [];

                if (!is_array($attachments)) {
                    // failsafe if DB has bad data
                    $attachments = json_decode((string) $attachments, true) ?? [];
                }

                $notice->first_attachment = $attachments[0] ?? null;
            });
        });

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }



    public function allNotices()
    {
        $notices = Notice::select('id', 'category_id', 'title', 'slug', 'publish_date', 'attachments', 'attachment_type', 'views', 'is_featured', 'status')
            ->with(['noticeCategory:id,name']) // Load category name
            ->where('status', 'published')
            ->orderBy('publish_date', 'DESC')
            ->get();

        // Extract first attachment + category name
        $notices->each(function ($notice) {
            $attachments = json_decode($notice->attachments, true);
            $notice->first_attachment = $attachments[0] ?? null;

            // Add category_name directly into response
            $notice->category_name = $notice->category->name ?? null;

            // Remove category relation if not needed
            unset($notice->category);
        });

        return response()->json([
            'success' => true,
            'data' => $notices
        ]);
    }

    public function noticeDetails($slug)
    {
        // Fetch notice
        $notice = Notice::with('noticeCategory')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$notice) {
            return response()->json([
                'success' => false,
                'message' => 'Notice not found'
            ], 404);
        }

        // Increase view count
        $notice->increment('views');

        // Decode attachments safely
        $attachments = [];
        if (!empty($notice->attachments)) {
            $attachments = is_string($notice->attachments)
                ? json_decode($notice->attachments, true)
                : (is_array($notice->attachments) ? $notice->attachments : []);
        }

        // Related notices (same category, excluding itself)
        $related = Notice::where('status', 'published')
            ->where('id', '!=', $notice->id)
            ->where('category_id', $notice->category_id)
            ->orderBy('publish_date', 'DESC')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'publish_date']);

        return response()->json([
            'success' => true,
            'data' => [
                'notice' => [
                    'id'               => $notice->id,
                    'title'            => $notice->title,
                    'slug'             => $notice->slug,
                    'body'             => $notice->body,
                    'publish_date'     => $notice->publish_date,
                    'attachments'      => $attachments,
                    'attachment_type'  => $notice->attachment_type,
                    'meta_title'       => $notice->meta_title,
                    'meta_tags'        => $notice->meta_tags,
                    'meta_description' => $notice->meta_description,
                    'views'            => $notice->views,
                    'is_featured'      => $notice->is_featured,
                    'category'         => $notice->category ? $notice->category->name : null,
                ],
                'related_notices' => $related
            ]
        ]);
    }

    // allNews
    public function allNews()
    {
        $news = News::select(
            'id',
            'title',
            'slug',
            'thumb_image',
            'banner_image',
            'summary',
            'published_at',
            'read_time',
            'category',
            'tags'
        )
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    public function newsDetails($slug)
    {
        // Load full news details
        $news = News::select(
            'id',
            'title',
            'slug',
            'thumb_image',
            'content_image',
            'banner_image',
            'summary',
            'content',
            'author',
            'published_at',
            'read_time',
            'category',
            'tags',
            'status'
        )
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Decode tags

        // Increase view count
        $news->increment('read_time');

        // Related news (same category, except itself)
        $relatedNews = News::select(
            'id',
            'title',
            'slug',
            'thumb_image',
            'published_at',
            'category'
        )
            ->where('category', $news->category)
            ->where('status', 'published')
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'DESC')
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'news' => $news,
                'related_news' => $relatedNews
            ]
        ]);
    }


    public function marquees()
    {
        // Featured news
        $news = News::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'DESC')
            ->select('id', 'title', 'slug', 'published_at')
            ->get()
            ->map(function ($item) {
                return [
                    'type'  => 'news',
                    'title' => $item->title,
                    'url'   => url('/api/v1/news/' . $item->slug), // DIRECT PAGE URL
                    'date'  => $item->published_at,
                ];
            });

        // Featured notices
        $notices = Notice::where('status', 'published')->where('is_featured', true)
            ->orderBy('publish_date', 'DESC')
            ->select('id', 'title', 'slug', 'publish_date')
            ->get()
            ->map(function ($item) {
                return [
                    'type'  => 'notice',
                    'title' => $item->title,
                    'url'   => url('/api/v1/notices/' . $item->slug), // DIRECT PAGE URL
                    'date'  => $item->publish_date,
                ];
            });

        // Alternate items (news, notice, news, notice...)
        $merged = [];
        $max = max($news->count(), $notices->count());

        for ($i = 0; $i < $max; $i++) {
            if (isset($news[$i])) {
                $merged[] = $news[$i];
            }
            if (isset($notices[$i])) {
                $merged[] = $notices[$i];
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $merged
        ]);
    }


    public function adminIndex()
    {
        $groups = AdminGroup::with([
            'offices' => fn($q) => $q->orderBy('position')
        ])
            ->orderBy('position')
            ->get();

        $data = $groups->map(function ($group) {

            return [
                'id'       => $group->id,
                'name'     => $group->name,
                'slug'     => $group->slug,
                'position' => $group->position,

                'offices' => $group->offices->map(function ($office) {

                    return [
                        'id'          => $office->id,
                        'title'       => $office->title,
                        'slug'        => $office->slug,
                        'description' => $office->description,

                        // SEO
                        'meta_title'        => $office->meta_title,
                        'meta_tags'         => $office->meta_tags,
                        'meta_description'  => $office->meta_description,

                        'total_sections' => $office->sections()->count(),
                        'total_members'  => $office->members()->count(),
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function adminOfficeDetails($slug)
    {
        $office = AdminOffice::where('slug', $slug)
            ->with([
                'sections' => fn($q) => $q->orderBy('position'),
                'members'  => fn($q) => $q->orderBy('position'),
            ])
            ->first();

        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found'
            ], 404);
        }



        $sections = $office->sections->map(function ($section) use ($office) {

            $members = $office->members
                ->where('section_id', $section->id)
                ->sortBy('position')
                ->values()
                ->map(function ($m) {

                    return [
                        'id'          => $m->id,
                        'name'        => $m->name,
                        'designation' => $m->designation,
                        'email'       => $m->email,
                        'phone'       => $m->phone,
                        'image'       => $m->image ? asset('storage/' . $m->image) : null,
                        'position'    => $m->position,
                    ];
                });

            return [
                'id'          => $section->id,
                'title'       => $section->title,
                'section_type' => $section->section_type,
                'content'     => $section->content,
                'extra'       => $section->extra,
                'position'    => $section->position,
                'members'     => $members,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'office' => [
                    'id'               => $office->id,
                    'title'            => $office->title,
                    'slug'             => $office->slug,
                    'description'      => $office->description,

                    // SEO
                    'meta_title'       => $office->meta_title,
                    'meta_tags'        => $office->meta_tags,
                    'meta_description' => $office->meta_description,

                    'total_sections'   => $office->sections->count(),
                    'total_members'    => $office->members->count(),
                ],

                'sections' => $sections
            ]
        ]);
    }




    public function admissionMenu()
    {
        $roots = Admission::active()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->active()->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        $data = $roots->map(fn($root) => $this->formatNode($root));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function admissionDetails(string $slug)
    {
        $item = Admission::active()
            ->where('slug', $slug)
            ->with(['children' => function ($q) {
                $q->active()->orderBy('position');
            }])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Admission item not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSingle($item),
        ]);
    }

    /**
     * Format node for menu tree (simplified).
     */
    protected function formatNode(Admission $node): array
    {
        return [
            'id'           => $node->id,
            'title'        => $node->title,
            'slug'         => $node->slug,
            'type'         => $node->type, // menu | page | external
            'external_url' => $node->external_url,
            'position'     => $node->position,
            'children'     => $node->children->map(fn($child) => $this->formatNode($child))->values(),
        ];
    }

    /**
     * Format full page for frontend renderer.
     */
    protected function formatSingle(Admission $item): array
    {
        return [
            'id'              => $item->id,
            'title'           => $item->title,
            'slug'            => $item->slug,
            'type'            => $item->type,
            'external_url'    => $item->external_url,
            'banner_image'    => $item->banner_image ? asset('storage/' . $item->banner_image) : null,
            'content'         => $item->type === 'page' ? $item->content : null,

            // SEO
            'meta_title'      => $item->meta_title,
            'meta_tags'       => $item->meta_tags,
            'meta_description' => $item->meta_description,

            'children' => $item->children->map(fn($child) => $this->formatNode($child))->values(),
        ];
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
