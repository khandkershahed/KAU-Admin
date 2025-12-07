<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomepageBuilderController extends Controller
{
    public function __construct()
    {
        // One permission for everything, adjust if you split view/edit later
        $this->middleware('permission:manage homepage')->only(['edit', 'update', 'preview']);
    }

    /**
     * Show builder page (single record)
     */
    public function edit()
    {
        $homepage = Homepage::first();

        if (!$homepage) {
            $homepage = Homepage::create([
                'section_layout' => $this->defaultLayout(),
            ]);
        } elseif (empty($homepage->section_layout)) {
            $homepage->section_layout = $this->defaultLayout();
            $homepage->save();
        }

        return view('admin.pages.homepage.builder', compact('homepage'));
    }

    /**
     * Update homepage (create if not exists)
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $homepage = Homepage::first();

            if (!$homepage) {
                $homepage = new Homepage();
            }

            $validator = Validator::make($request->all(), [
                // Banner 1–3 text
                'hero1_title'         => 'nullable|string|max:255',
                'hero1_subtitle'      => 'nullable|string',
                'hero1_button_text'   => 'nullable|string|max:255',
                'hero1_button_url'    => 'nullable|string|max:1000',

                'hero2_title'         => 'nullable|string|max:255',
                'hero2_subtitle'      => 'nullable|string',
                'hero2_button_text'   => 'nullable|string|max:255',
                'hero2_button_url'    => 'nullable|string|max:1000',

                'hero3_title'         => 'nullable|string|max:255',
                'hero3_subtitle'      => 'nullable|string',
                'hero3_button_text'   => 'nullable|string|max:255',
                'hero3_button_url'    => 'nullable|string|max:1000',

                // Banner images
                'hero1_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'hero2_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'hero3_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

                // VC
                'vc_section_title'        => 'nullable|string|max:255',
                'vc_section_button_text'  => 'nullable|string|max:255',
                'vc_section_button_url'   => 'nullable|string|max:1000',
                'vc_message'              => 'nullable|string',
                'vc_name'                 => 'nullable|string|max:255',
                'vc_designation'          => 'nullable|string|max:255',
                'vc_photo'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

                // Explore KAU
                'explore_section_title'   => 'nullable|string|max:255',
                'explore_section_subtitle'=> 'nullable|string',
                'explore_boxes'           => 'nullable|array',
                'explore_boxes.*.icon'    => 'nullable|string|max:255',
                'explore_boxes.*.title'   => 'nullable|string|max:255',
                'explore_boxes.*.link'    => 'nullable|string|max:1000',

                // Faculties / Programs
                'faculty_title'          => 'nullable|string|max:255',
                'faculty_subtitle'       => 'nullable|string',

                // KAU at a Glance
                'at_a_glance_title'      => 'nullable|string|max:255',
                'at_a_glance_subtitle'   => 'nullable|string',
                'at_a_glance_boxes'      => 'nullable|array',
                'at_a_glance_boxes.*.icon'   => 'nullable|string|max:255',
                'at_a_glance_boxes.*.title'  => 'nullable|string|max:255',
                'at_a_glance_boxes.*.number' => 'nullable|integer|min:0',

                // About
                'about_badge'                => 'nullable|string|max:255',
                'about_title'                => 'nullable|string|max:255',
                'about_subtitle'             => 'nullable|string|max:255',
                'about_description'          => 'nullable|string',
                'about_experience_badge'     => 'nullable|string|max:255',
                'about_experience_title'     => 'nullable|string|max:255',
                'about_image1'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image2'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image3'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image4'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image5'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

                // Important links
                'important_links_title'      => 'nullable|string|max:255',
                'important_links_description'=> 'nullable|string',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // ====== SIMPLE FIELDS ======
            $homepage->hero1_title       = $request->hero1_title;
            $homepage->hero1_subtitle    = $request->hero1_subtitle;
            $homepage->hero1_button_text = $request->hero1_button_text;
            $homepage->hero1_button_url  = $request->hero1_button_url;

            $homepage->hero2_title       = $request->hero2_title;
            $homepage->hero2_subtitle    = $request->hero2_subtitle;
            $homepage->hero2_button_text = $request->hero2_button_text;
            $homepage->hero2_button_url  = $request->hero2_button_url;

            $homepage->hero3_title       = $request->hero3_title;
            $homepage->hero3_subtitle    = $request->hero3_subtitle;
            $homepage->hero3_button_text = $request->hero3_button_text;
            $homepage->hero3_button_url  = $request->hero3_button_url;

            $homepage->vc_section_title       = $request->vc_section_title;
            $homepage->vc_section_button_text = $request->vc_section_button_text;
            $homepage->vc_section_button_url  = $request->vc_section_button_url;
            $homepage->vc_message             = $request->vc_message;
            $homepage->vc_name                = $request->vc_name;
            $homepage->vc_designation         = $request->vc_designation;

            $homepage->explore_section_title    = $request->explore_section_title;
            $homepage->explore_section_subtitle = $request->explore_section_subtitle;
            $homepage->explore_boxes            = $this->normalizeRepeater($request->explore_boxes);

            $homepage->faculty_title    = $request->faculty_title;
            $homepage->faculty_subtitle = $request->faculty_subtitle;

            $homepage->at_a_glance_title    = $request->at_a_glance_title;
            $homepage->at_a_glance_subtitle = $request->at_a_glance_subtitle;
            $homepage->at_a_glance_boxes    = $this->normalizeRepeater($request->at_a_glance_boxes);

            $homepage->about_badge            = $request->about_badge;
            $homepage->about_title            = $request->about_title;
            $homepage->about_subtitle         = $request->about_subtitle;
            $homepage->about_description      = $request->about_description;
            $homepage->about_experience_badge = $request->about_experience_badge;
            $homepage->about_experience_title = $request->about_experience_title;

            $homepage->important_links_title       = $request->important_links_title;
            $homepage->important_links_description = $request->important_links_description;

            // ====== SECTION LAYOUT (order + enabled) ======
            $homepage->section_layout = $this->buildLayoutFromRequest($request);

            // ====== IMAGE UPLOADS (same style as NewsController) ======
            // Banner images
            $imageFields = [
                'hero1_image' => ['column' => 'hero1_image_path', 'path' => 'homepage/banner'],
                'hero2_image' => ['column' => 'hero2_image_path', 'path' => 'homepage/banner'],
                'hero3_image' => ['column' => 'hero3_image_path', 'path' => 'homepage/banner'],
                'vc_photo'    => ['column' => 'vc_photo_path',    'path' => 'homepage/vc'],
            ];

            foreach ($imageFields as $field => $config) {
                $column = $config['column'];
                $path   = $config['path'];

                // explicit remove flag from Metronic image-input
                if ($request->boolean($field . '_remove')) {
                    if (!empty($homepage->$column)) {
                        Storage::delete('public/' . $homepage->$column);
                    }
                    $homepage->$column = null;
                    continue;
                }

                if ($request->hasFile($field)) {
                    if (!empty($homepage->$column)) {
                        Storage::delete('public/' . $homepage->$column);
                    }
                    $upload = customUpload($request->file($field), $path);
                    if ($upload['status'] === 0) {
                        DB::rollBack();
                        return redirect()->back()
                            ->withInput()
                            ->with('error', $upload['error_message']);
                    }
                    $homepage->$column = $upload['file_path'];
                }
            }

            // About images (fixed 5 slots)
            $aboutImages = $homepage->about_section_images ?? [];
            for ($i = 1; $i <= 5; $i++) {
                $field  = "about_image{$i}";
                $remove = "{$field}_remove";

                if ($request->boolean($remove)) {
                    if (!empty($aboutImages[$i])) {
                        Storage::delete('public/' . $aboutImages[$i]);
                    }
                    unset($aboutImages[$i]);
                    continue;
                }

                if ($request->hasFile($field)) {
                    if (!empty($aboutImages[$i])) {
                        Storage::delete('public/' . $aboutImages[$i]);
                    }
                    $upload = customUpload($request->file($field), 'homepage/about');
                    if ($upload['status'] === 0) {
                        DB::rollBack();
                        return redirect()->back()
                            ->withInput()
                            ->with('error', $upload['error_message']);
                    }
                    $aboutImages[$i] = $upload['file_path'];
                }
            }
            ksort($aboutImages);
            $homepage->about_section_images = array_values($aboutImages);

            $homepage->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Homepage updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating homepage: ' . $e->getMessage());
        }
    }

    /**
     * AJAX live preview (no DB changes, no file uploads)
     */
    public function preview(Request $request)
    {
        $homepage = Homepage::first() ?? new Homepage();

        // we reuse mapping but ignore file uploads
        $homepage->hero1_title       = $request->hero1_title;
        $homepage->hero1_subtitle    = $request->hero1_subtitle;
        $homepage->hero1_button_text = $request->hero1_button_text;
        $homepage->hero1_button_url  = $request->hero1_button_url;

        $homepage->hero2_title       = $request->hero2_title;
        $homepage->hero2_subtitle    = $request->hero2_subtitle;
        $homepage->hero2_button_text = $request->hero2_button_text;
        $homepage->hero2_button_url  = $request->hero2_button_url;

        $homepage->hero3_title       = $request->hero3_title;
        $homepage->hero3_subtitle    = $request->hero3_subtitle;
        $homepage->hero3_button_text = $request->hero3_button_text;
        $homepage->hero3_button_url  = $request->hero3_button_url;

        $homepage->vc_section_title       = $request->vc_section_title;
        $homepage->vc_section_button_text = $request->vc_section_button_text;
        $homepage->vc_section_button_url  = $request->vc_section_button_url;
        $homepage->vc_message             = $request->vc_message;
        $homepage->vc_name                = $request->vc_name;
        $homepage->vc_designation         = $request->vc_designation;

        $homepage->explore_section_title    = $request->explore_section_title;
        $homepage->explore_section_subtitle = $request->explore_section_subtitle;
        $homepage->explore_boxes            = $this->normalizeRepeater($request->explore_boxes);

        $homepage->faculty_title    = $request->faculty_title;
        $homepage->faculty_subtitle = $request->faculty_subtitle;

        $homepage->at_a_glance_title    = $request->at_a_glance_title;
        $homepage->at_a_glance_subtitle = $request->at_a_glance_subtitle;
        $homepage->at_a_glance_boxes    = $this->normalizeRepeater($request->at_a_glance_boxes);

        $homepage->about_badge            = $request->about_badge;
        $homepage->about_title            = $request->about_title;
        $homepage->about_subtitle         = $request->about_subtitle;
        $homepage->about_description      = $request->about_description;
        $homepage->about_experience_badge = $request->about_experience_badge;
        $homepage->about_experience_title = $request->about_experience_title;

        $homepage->important_links_title       = $request->important_links_title;
        $homepage->important_links_description = $request->important_links_description;

        $homepage->section_layout = $this->buildLayoutFromRequest($request);

        $html = view('admin.pages.homepage.preview', compact('homepage'))->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
        ]);
    }

    /**
     * Normalize repeater array (remove empty rows)
     */
    protected function normalizeRepeater($items): ?array
    {
        if (!is_array($items)) {
            return null;
        }

        $clean = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            // keep if at least one non-empty value
            if (implode('', array_map('trim', $item)) === '') {
                continue;
            }

            $clean[] = [
                'icon'   => $item['icon']   ?? null,
                'title'  => $item['title']  ?? null,
                'link'   => $item['link']   ?? ($item['number'] ?? null),
                'number' => $item['number'] ?? null,
            ];
        }

        return $clean ?: null;
    }

    /**
     * Build section layout array from request
     */
    protected function buildLayoutFromRequest(Request $request): array
    {
        $sectionKeys    = $request->input('section_keys', []);
        $sectionEnabled = $request->input('section_enabled', []);

        $layout = [];
        foreach ($sectionKeys as $key) {
            $layout[] = [
                'key'     => $key,
                'label'   => $this->getSectionLabel($key),
                'enabled' => isset($sectionEnabled[$key]) && $sectionEnabled[$key] ? true : false,
            ];
        }

        return $layout ?: $this->defaultLayout();
    }

    protected function defaultLayout(): array
    {
        return [
            ['key' => 'banner',         'label' => 'Banner Slider',        'enabled' => true],
            ['key' => 'vc',             'label' => 'Vice Chancellor',      'enabled' => true],
            ['key' => 'explore',        'label' => 'Explore KAU',          'enabled' => true],
            ['key' => 'faculty',        'label' => 'Faculties / Programs', 'enabled' => true],
            ['key' => 'at_a_glance',    'label' => 'KAU at a Glance',      'enabled' => true],
            ['key' => 'about',          'label' => 'About Section',        'enabled' => true],
            ['key' => 'important_links','label' => 'Important Links',      'enabled' => true],
        ];
    }

    protected function getSectionLabel(string $key): string
    {
        return [
            'banner'         => 'Banner Slider',
            'vc'             => 'Vice Chancellor',
            'explore'        => 'Explore KAU',
            'faculty'        => 'Faculties / Programs',
            'at_a_glance'    => 'KAU at a Glance',
            'about'          => 'About Section',
            'important_links'=> 'Important Links',
        ][$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}
