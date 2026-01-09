<?php

namespace App\Http\Controllers\Frontend\Api;


use App\Models\Faq;
use App\Models\News;
use App\Models\Event;
use App\Models\Terms;
use App\Models\Notice;
use App\Models\Contact;
use App\Models\Privacy;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Homepage;
use App\Models\AboutPage;
use App\Models\Admission;
use App\Models\EventType;
use App\Models\HomePopup;
use App\Models\AdminGroup;
use App\Models\AdminOffice;
use Illuminate\Http\Request;
use App\Models\HomepageAbout;
use App\Models\HomepageBanner;
use App\Models\HomepageGlance;
use App\Models\NoticeCategory;
use App\Models\HomepageExplore;
use App\Models\HomepageFaculty;
use App\Models\HomepageSection;
use App\Models\AcademicMenuGroup;
use App\Models\HomepageVcMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\EventTypeResource;
use Illuminate\Support\Facades\Validator;

class HomeApiController extends Controller
{
    public function siteInformations()
    {
        $settings = DB::table('settings')->first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Settings not configured.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'settings' => [

                'website_name'       => $settings->website_name,
                'website_name_bn'    => $settings->website_name_bn,
                'site_title'         => $settings->site_title,
                'site_motto'         => $settings->site_motto,
                'footer_description' => $settings->footer_description,

                'branding' => [
                    'site_logo_white' => $settings->site_logo_white
                        ? URL::to('storage/' . $settings->site_logo_white) : null,

                    'site_logo_black' => $settings->site_logo_black
                        ? URL::to('storage/' . $settings->site_logo_black) : null,

                    'site_favicon' => $settings->site_favicon
                        ? URL::to('storage/' . $settings->site_favicon) : null,

                    'theme_color'           => $settings->theme_color,
                    'secondary_theme_color' => $settings->secondary_theme_color,
                    // 'dark_mode'  => (bool) $settings->dark_mode,
                ],


                'contact' => [
                    'emails'   => json_decode($settings->emails, true) ?? [],
                    'phones'   => json_decode($settings->phone, true) ?? [],
                    'addresses' => json_decode($settings->addresses, true) ?? [],
                    'contact_person' => json_decode($settings->contact_person, true) ?? [],
                ],


                'seo' => [
                    'site_url'        => $settings->site_url,
                    'meta_title'      => $settings->meta_title,
                    'meta_keyword'    => $settings->meta_keyword,
                    'meta_tags'       => $settings->meta_tags,
                    'meta_description' => $settings->meta_description,

                    'og_image' => $settings->og_image
                        ? URL::to('storage/' . $settings->og_image) : null,

                    'og_title'       => $settings->og_title,
                    'og_description' => $settings->og_description,
                    'canonical_url'  => $settings->canonical_url,
                ],

                'social_links' => collect(json_decode($settings->social_links, true) ?? [])
                    ->sortBy('order')
                    ->values()
                    ->all(),

                'footer_links' => collect(json_decode($settings->footer_links, true) ?? [])
                    ->sortBy('order')
                    ->values()
                    ->all(),

                'business_hours' => json_decode($settings->business_hours, true) ?? [],
                'copyright_text' => $settings->copyright_text,
                'developer' => [
                    'text' => $settings->developer_text,
                    'url'  => $settings->developer_link,
                ],



                'custom_settings' => json_decode($settings->custom_settings, true) ?? [],

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
            $attachments = $notice->attachments ?? [];
            if (!is_array($attachments)) {
                $attachments = json_decode((string) $attachments, true) ?? [];
            }
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
                        'label'       => $m->label,
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





    public function homepageShow()
    {
        $sections = HomepageSection::orderBy('position')->get();

        $banners = HomepageBanner::orderBy('position')->get();

        $vc = HomepageVcMessage::first();

        $explore = HomepageExplore::with('items')->first();
        $glance  = HomepageGlance::with('items')->first();

        $faculty = HomepageFaculty::first();
        $about   = HomepageAbout::first();

        return response()->json([
            // ================================
            // SECTION ORDER + ACTIVE DISABLED
            // ================================
            'sections' => $sections->map(function ($s) {
                return [
                    'section_key' => $s->section_key,
                    'is_active'   => (bool) $s->is_active,
                    'position'    => (int) $s->position,
                ];
            }),

            // ================================
            // BANNERS
            // ================================
            'banners' => $banners->map(function ($b) {
                return [
                    'id'          => $b->id,
                    'title'       => $b->title,
                    'subtitle'    => $b->subtitle,
                    'button_text' => $b->button_text,
                    'button_url'  => $b->button_url,
                    'image_url'   => $b->image_path ? asset('storage/' . $b->image_path) : null,
                    'position'    => (int) $b->position,
                ];
            }),

            // ================================
            // VC MESSAGE
            // ================================
            'vc_message' => $vc ? [
                'vc_name'        => $vc->vc_name,
                'vc_designation' => $vc->vc_designation,
                'vc_image_url'   => $vc->vc_image ? asset('storage/' . $vc->vc_image) : null,
                'message_title'  => $vc->message_title,
                'message_text'   => $vc->message_text,
                'button_name'    => $vc->button_name,
                'button_url'     => $vc->button_url,
            ] : null,

            // ================================
            // EXPLORE KAU SECTION
            // ================================
            'explore' => $explore ? [
                'section_title' => $explore->section_title,
                'items'         => $explore->items
                    ->sortBy('position')
                    ->values()
                    ->map(function ($item) {
                        return [
                            'icon'     => $item->icon,
                            'title'    => $item->title,
                            'url'      => $item->url,
                            'position' => (int) $item->position,
                        ];
                    }),
            ] : null,

            // ================================
            // KAU AT A GLANCE
            // ================================
            'glance' => $glance ? [
                'section_title'    => $glance->section_title,
                'section_subtitle' => $glance->section_subtitle,
                'items'            => $glance->items
                    ->sortBy('position')
                    ->values()
                    ->map(function ($item) {
                        return [
                            'icon'     => $item->icon,
                            'title'    => $item->title,
                            'number'   => $item->number,
                            'position' => (int) $item->position,
                        ];
                    }),
            ] : null,

            // ================================
            // FACULTY (title only)
            // ================================
            'faculty' => $faculty ? [
                'section_title'    => $faculty->section_title,
                'section_subtitle' => $faculty->section_subtitle,
            ] : null,

            // ================================
            // ABOUT SECTION
            // ================================
            'about' => $about ? [
                'badge'            => $about->badge,
                'title'            => $about->title,
                'subtitle'         => $about->subtitle,
                'description'      => $about->description,
                'experience_badge' => $about->experience_badge,
                'experience_title' => $about->experience_title,

                // Return only non-null images, mapped to full URLs
                'images' => collect($about->images ?? [])
                    ->map(function ($path) {
                        return $path ? asset('storage/' . $path) : null;
                    })
                    ->filter()
                    ->values(),
            ] : null,
        ]);
    }

    public function allAboutPages()
    {
        $pages = AboutPage::published()
            ->ordered()
            ->get();

        return response()->json([
            'menu' => $pages->map(function (AboutPage $p) {
                return [
                    'id'          => $p->id,
                    'title'       => $p->title,
                    'menu_label'  => $p->menu_label ?? $p->title,
                    'slug'        => $p->slug,
                    'is_featured' => (bool) $p->is_featured,
                    'position'    => (int) $p->menu_order,
                ];
            }),
            // helpful so frontend knows default
            'default_slug' => optional($pages->first())->slug,
        ]);
    }


    public function aboutPageDetails(string $slug)
    {
        $page = AboutPage::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'page' => [
                'id'              => $page->id,
                'title'           => $page->title,
                'slug'            => $page->slug,
                'menu_label'      => $page->menu_label ?? $page->title,

                'banner_title'    => $page->banner_title ?? $page->title,
                'banner_subtitle' => $page->banner_subtitle,
                'banner_icon'     => $page->banner_icon,
                'banner_image'    => $page->banner_image
                    ? asset('storage/' . $page->banner_image)
                    : null,

                'excerpt'         => $page->excerpt,
                'content'         => $page->content, // HTML

                'meta_title'       => $page->meta_title,
                'meta_tags'        => $page->meta_tags,
                'meta_description' => $page->meta_description,

                'is_featured'      => (bool) $page->is_featured,
                'position'         => (int) $page->menu_order,
            ],
        ]);
    }

    // footer
    public function footer()
    {
        $setting = Setting::first();

        return response()->json([
            'footer_description' => $setting->footer_description,
            'footer_links'       => $setting->footer_links,
            'contact_person'     => $setting->contact_person,
            'emails'             => $setting->emails,
            'phone'              => $setting->phone,
            'addresses'          => $setting->addresses,
            'social_links'       => $setting->social_links,
            'copyright_text'     => $setting->copyright_text,
            'developer_text'     => $setting->developer_text,
            'developer_link'     => $setting->developer_link,
            'website_url'        => $setting->website_url,
        ]);
    }


    public function academicNested(): JsonResponse
    {
        // Cache full nested structure for 10 minutes to make Next.js super fast
        $result = Cache::remember('api_academics_nested', 600, function () {
            $groups = AcademicMenuGroup::with([
                'units.departments',
                'units.staffSections.department',
                'units.staffSections.members', // no .links relation anymore
            ])
                ->active()
                ->ordered()
                ->get();

            return [
                'Academics' => $groups->map(function ($group) {
                    return [
                        'id'       => $group->id,
                        'title'    => $group->title,
                        'slug'     => $group->slug,     // "faculty", "institute"
                        'type'     => 'menu',
                        'position' => (int) $group->position,
                        'pages'    => $group->units
                            ->sortBy('menu_order')
                            ->values()
                            ->map(function ($unit) {
                                $config     = $unit->config ?? [];
                                $home       = $config['home'] ?? [];
                                $about      = $config['about'] ?? null;
                                $facilities = $config['facilities'] ?? null;
                                $academic   = $config['academic'] ?? null;
                                $research   = $config['research'] ?? null;
                                $programs   = $config['programs'] ?? null;
                                $contact    = $config['contact'] ?? null;

                                // departments list (for department grid)
                                $deptList = $unit->departments
                                    ->sortBy('position')
                                    ->values()
                                    ->map(function ($d) {
                                        return [
                                            'title'      => $d->title,
                                            'short_code' => $d->short_code,
                                        ];
                                    });

                                // faculty_members block (departments -> sections -> members -> links)
                                $facultyMembers = null;

                                if ($unit->staffSections->count()) {
                                    // group sections by department
                                    $byDept = $unit->staffSections
                                        ->sortBy('position')
                                        ->groupBy('department_id');

                                    $facultyMembers = [
                                        [
                                            'endpoint'  => $config['faculty_members']['endpoint'] ?? '/faculty-member',
                                            'group_by'  => 'department',
                                            'departments' => $byDept->map(function ($sections, $departmentId) use ($unit) {
                                                $dept = $unit->departments->firstWhere('id', (int) $departmentId);

                                                return [
                                                    'title'      => $dept ? $dept->title : null,
                                                    'short_code' => $dept ? $dept->short_code : null,
                                                    'sections'   => $sections->map(function ($section) {
                                                        return [
                                                            'id'       => $section->id,
                                                            'title'    => $section->title,
                                                            'position' => (int) $section->position,
                                                            'members'  => $section->members
                                                                ->sortBy('position')
                                                                ->values()
                                                                ->map(function ($m) {
                                                                    return [
                                                                        'id'          => $m->id,
                                                                        'name'        => $m->name,
                                                                        'designation' => $m->designation,
                                                                        'email'       => $m->email,
                                                                        'phone'       => $m->phone,
                                                                        'image'       => $m->image_path
                                                                            ? asset('storage/' . $m->image_path)
                                                                            : null,
                                                                        'position'    => (int) $m->position,
                                                                        // links is now a JSON array on the model
                                                                        'links'       => $m->links ?: [],
                                                                    ];
                                                                }),
                                                        ];
                                                    }),
                                                ];
                                            })->values(),
                                        ],
                                    ];
                                }

                                $contentsBlock = [
                                    [
                                        'base_url' => $unit->base_url,
                                        'home' => [
                                            [
                                                'endpoint'            => $home['endpoint'] ?? '/',
                                                'layout'              => $home['layout'] ?? 'faculty_home',
                                                'has_hero'            => (bool) ($home['has_hero'] ?? true),
                                                'has_department_grid' => (bool) ($home['has_department_grid'] ?? true),
                                            ],
                                        ],
                                        'about' => $about ? [
                                            [
                                                'endpoint'      => $about['endpoint'] ?? '/about',
                                                'section_title' => $about['section_title'] ?? ('About ' . $unit->short_name),
                                            ],
                                        ] : [],
                                        'departments' => [
                                            [
                                                'endpoint'    => $config['departments']['endpoint'] ?? '/departments',
                                                'departments' => $deptList,
                                            ],
                                        ],
                                        'facilities' => $facilities ? [
                                            [
                                                'endpoint'      => $facilities['endpoint'] ?? '/facilities',
                                                'section_title' => $facilities['section_title'] ?? 'Facilities',
                                            ],
                                        ] : [],
                                        'faculty_members' => $facultyMembers ?: [],
                                        'academic' => $academic ? [
                                            [
                                                'endpoint'   => $academic['endpoint'] ?? '/academic',
                                                'menu_label' => $academic['menu_label'] ?? 'Academic',
                                                'sub_pages'  => $academic['sub_pages'] ?? [],
                                            ],
                                        ] : [],
                                        'research' => $research ? [
                                            [
                                                'endpoint'   => $research['endpoint'] ?? '/research',
                                                'menu_label' => $research['menu_label'] ?? 'Research',
                                            ],
                                        ] : [],
                                        'programs' => $programs ? [
                                            [
                                                'endpoint' => $programs['endpoint'],
                                                'types'    => $programs['types'] ?? [],
                                            ],
                                        ] : [],
                                        'contact' => $contact ? [
                                            [
                                                'endpoint'      => $contact['endpoint'],
                                                'section_title' => $contact['section_title'] ?? 'Contact Information',
                                            ],
                                        ] : [],
                                    ],
                                ];

                                return [
                                    'icon'              => $unit->icon,
                                    'title'             => $unit->name,
                                    'slug'              => $unit->slug,
                                    'short_name'        => $unit->short_name,
                                    'short_description' => $unit->short_description,
                                    'button_name'       => $unit->button_name,
                                    'position'          => (int) $unit->menu_order,
                                    'contents'          => $contentsBlock,
                                ];
                            }),
                    ];
                })->values(), // just to keep it nice and indexed 0..n
            ];
        });

        return response()->json($result);
    }

    public function homePopup()
    {
        $popup = HomePopup::where('status', 'active')->first();

        if (!$popup) {
            return response()->json(null);
        }

        return response()->json([
            'title'        => $popup->title,
            'content'      => $popup->content,
            'button_name'  => $popup->button_name,
            'button_link'  => $popup->button_link,
        ]);
    }

    // faqs grouped by category
    public function faqs()
    {
        $faqs = Faq::where('status', 'active')
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'faqs'     => $items->map(function ($item) {
                        return [
                            'question' => $item->question,
                            'answer'   => $item->answer,
                            'order'    => (int) $item->order,
                        ];
                    })->values(),
                ];
            })->values();
        return response()->json([
            'success' => true,
            'data'    => $faqs,
        ]);
    }

    public function policy()
    {
        $privacy = Privacy::where('status', 'active')->first();

        if (!$privacy) {
            return response()->json(null);
        }

        return response()->json([
            'title'       => $privacy->title,
            'content'     => $privacy->content,
            'effective_date' => $privacy->effective_date,
            'expiration_date' => $privacy->expiration_date,
        ]);
    }

    public function terms()
    {
        $terms = Terms::where('status', 'active')->first();

        if (!$terms) {
            return response()->json(null);
        }

        return response()->json([
            'title'       => $terms->title,
            'content'     => $terms->content,
            'effective_date' => $terms->effective_date,
            'expiration_date' => $terms->expiration_date,
        ]);
    }
}
