<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageAbout;
use App\Models\HomepageBanner;
use App\Models\HomepageExplore;
use App\Models\HomepageExploreItem;
use App\Models\HomepageFaculty;
use App\Models\HomepageGlance;
use App\Models\HomepageGlanceItem;
use App\Models\HomepageSection;
use App\Models\HomepageVcMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomepageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage homepage')->only(['edit', 'update']);
    }

    /**
     * Builder page
     */
    public function edit()
    {
        // Ensure default sections exist
        $this->ensureDefaultSections();

        $sections = HomepageSection::orderBy('position')->get();

        $banners = HomepageBanner::orderBy('position')->get();

        $vc = HomepageVcMessage::first() ?? HomepageVcMessage::create([]);

        $explore = HomepageExplore::first() ?? HomepageExplore::create([]);
        $exploreItems = $explore->items()->orderBy('position')->get();

        $faculty = HomepageFaculty::first() ?? HomepageFaculty::create([]);

        $glance = HomepageGlance::first() ?? HomepageGlance::create([]);
        $glanceItems = $glance->items()->orderBy('position')->get();

        $about = HomepageAbout::first() ?? HomepageAbout::create([
            'images' => [],
        ]);

        return view('admin.pages.homepage.builder', compact(
            'sections',
            'banners',
            'vc',
            'explore',
            'exploreItems',
            'faculty',
            'glance',
            'glanceItems',
            'about'
        ));
    }

    /**
     * Handle full builder save
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            // No "required" rules, everything optional
            $validator = Validator::make($request->all(), [
                // Sections
                'sections'                     => 'nullable|array',
                'sections.*.id'                => 'nullable|integer|exists:homepage_sections,id',
                'sections.*.is_active'         => 'nullable|boolean',
                'section_order'                => 'nullable|array',

                // Banners
                'banners'                      => 'nullable|array',
                'banners.*.id'                 => 'nullable|integer|exists:homepage_banners,id',
                'banners.*.title'              => 'nullable|string|max:255',
                'banners.*.subtitle'           => 'nullable|string',
                'banners.*.button_text'        => 'nullable|string|max:255',
                'banners.*.button_url'         => 'nullable|string|max:1000',
                'banner_images'                => 'nullable|array',
                'banner_images.*'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

                // VC
                'vc_name'                      => 'nullable|string|max:255',
                'vc_designation'               => 'nullable|string|max:255',
                'vc_image'                     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'message_title'                => 'nullable|string|max:255',
                'message_text'                 => 'nullable|string',
                'vc_button_name'               => 'nullable|string|max:255',
                'vc_button_url'                => 'nullable|string|max:1000',

                // Explore
                'explore_section_title'        => 'nullable|string|max:255',
                'explore_items'                => 'nullable|array',
                'explore_items.*.id'           => 'nullable|integer|exists:homepage_explore_items,id',
                'explore_items.*.icon'         => 'nullable|string|max:255',
                'explore_items.*.title'        => 'nullable|string|max:255',
                'explore_items.*.url'          => 'nullable|string|max:1000',

                // Faculty
                'faculty_section_title'        => 'nullable|string|max:255',
                'faculty_section_subtitle'     => 'nullable|string|max:255',

                // Glance
                'glance_section_title'         => 'nullable|string|max:255',
                'glance_section_subtitle'      => 'nullable|string|max:255',
                'glance_items'                 => 'nullable|array',
                'glance_items.*.id'            => 'nullable|integer|exists:homepage_glance_items,id',
                'glance_items.*.icon'          => 'nullable|string|max:255',
                'glance_items.*.title'         => 'nullable|string|max:255',
                'glance_items.*.number'        => 'nullable|string|max:255',

                // About
                'about_badge'                  => 'nullable|string|max:255',
                'about_title'                  => 'nullable|string|max:255',
                'about_subtitle'               => 'nullable|string|max:255',
                'about_description'            => 'nullable|string',
                'about_experience_badge'       => 'nullable|string|max:255',
                'about_experience_title'       => 'nullable|string|max:255',
                'about_image_1'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image_2'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image_3'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image_4'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'about_image_5'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                DB::rollBack();
                return redirect()->back()->withInput();
            }

            // SECTIONS: order + visibility
            $this->updateSections($request);

            // BANNERS
            $this->updateBanners($request);

            // VC MESSAGE
            $this->updateVcMessage($request);

            // EXPLORE
            $this->updateExplore($request);

            // FACULTY
            $this->updateFaculty($request);

            // GLANCE
            $this->updateGlance($request);

            // ABOUT
            $this->updateAbout($request);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Homepage updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating homepage: ' . $e->getMessage());
        }
    }


    public function preview(Request $request)
    {
        $this->ensureDefaultSections();

        $sections = HomepageSection::orderBy('position')->get();
        $banners  = HomepageBanner::orderBy('position')->get();
        $vc       = HomepageVcMessage::first();
        $explore  = HomepageExplore::with('items')->first();
        $faculty  = HomepageFaculty::first();
        $glance   = HomepageGlance::with('items')->first();
        $about    = HomepageAbout::first();

        return view('admin.pages.homepage.preview', compact(
            'sections',
            'banners',
            'vc',
            'explore',
            'faculty',
            'glance',
            'about'
        ));
    }


    public function sortSections(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:homepage_sections,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->order as $index => $id) {
                HomepageSection::where('id', $id)->update([
                    'position' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Section order saved.',
        ]);
    }

    public function toggleSection(Request $request)
    {
        $data = $request->validate([
            'id'        => 'required|integer|exists:homepage_sections,id',
            'is_active' => 'required|boolean',
        ]);

        $section = HomepageSection::findOrFail($data['id']);
        $section->is_active = $data['is_active'];
        $section->save();

        return response()->json([
            'success' => true,
            'message' => $section->is_active
                ? 'Section enabled successfully.'
                : 'Section disabled successfully.',
        ]);
    }

    /* ==================== HELPERS ==================== */

    protected function ensureDefaultSections(): void
    {
        $defaults = [
            ['section_key' => HomepageSection::KEY_BANNER,  'position' => 1],
            ['section_key' => HomepageSection::KEY_VC,      'position' => 2],
            ['section_key' => HomepageSection::KEY_EXPLORE, 'position' => 3],
            ['section_key' => HomepageSection::KEY_FACULTY, 'position' => 4],
            ['section_key' => HomepageSection::KEY_GLANCE,  'position' => 5],
            ['section_key' => HomepageSection::KEY_ABOUT,   'position' => 6],
        ];

        foreach ($defaults as $data) {
            HomepageSection::firstOrCreate(
                ['section_key' => $data['section_key']],
                ['position' => $data['position'], 'is_active' => true]
            );
        }
    }

    protected function updateSections(Request $request): void
    {
        $sectionOrder = $request->input('section_order', []); // array of ids in order
        $sectionsData = $request->input('sections', []);

        $position = 1;
        foreach ($sectionOrder as $sectionId) {
            /** @var HomepageSection|null $section */
            $section = HomepageSection::find($sectionId);
            if (!$section) {
                continue;
            }

            $section->position = $position++;
            $section->is_active = !empty($sectionsData[$sectionId]['is_active']);
            $section->save();
        }
    }

    protected function updateBanners(Request $request): void
    {
        $items = $request->input('banners', []);
        $files = $request->file('banner_images', []);

        // Enforce max 3 banners logically
        if (count($items) > 3) {
            $items = array_slice($items, 0, 3);
        }

        $existingIds = [];
        $position = 1;

        foreach ($items as $index => $item) {
            $id = $item['id'] ?? null;

            if ($id) {
                $banner = HomepageBanner::find($id);
                if (!$banner) continue;
            } else {
                $banner = new HomepageBanner();
            }

            $banner->title       = $item['title']       ?? null;
            $banner->subtitle    = $item['subtitle']    ?? null;
            $banner->button_text = $item['button_text'] ?? null;
            $banner->button_url  = $item['button_url']  ?? null;
            $banner->position    = $position++;

            // remove image?
            if (!empty($item['remove_image']) && $item['remove_image'] == 1 && $banner->image_path) {
                Storage::delete('public/' . $banner->image_path);
                $banner->image_path = null;
            }

            // upload new image?
            if (!empty($files[$index] ?? null)) {
                $file = $files[$index];
                if ($banner->image_path) {
                    Storage::delete('public/' . $banner->image_path);
                }
                $upload = customUpload($file, 'homepage/banners');
                if ($upload['status'] === 0) {
                    throw new \RuntimeException($upload['error_message']);
                }
                $banner->image_path = $upload['file_path'];
            }

            $banner->save();
            $existingIds[] = $banner->id;
        }

        // Delete removed banners
        if (!empty($existingIds)) {
            $toDelete = HomepageBanner::whereNotIn('id', $existingIds)->get();
            foreach ($toDelete as $b) {
                if ($b->image_path) {
                    Storage::delete('public/' . $b->image_path);
                }
                $b->delete();
            }
        } else {
            // If no items submitted, delete all
            foreach (HomepageBanner::all() as $b) {
                if ($b->image_path) {
                    Storage::delete('public/' . $b->image_path);
                }
                $b->delete();
            }
        }
    }

    protected function updateVcMessage(Request $request): void
    {
        $vc = HomepageVcMessage::first() ?? new HomepageVcMessage();

        $vc->vc_name         = $request->vc_name;
        $vc->vc_designation  = $request->vc_designation;
        $vc->message_title   = $request->message_title;
        $vc->message_text    = $request->message_text;
        $vc->button_name     = $request->vc_button_name;
        $vc->button_url      = $request->vc_button_url;

        // remove image?
        if ($request->boolean('vc_image_remove') && $vc->vc_image) {
            Storage::delete('public/' . $vc->vc_image);
            $vc->vc_image = null;
        }

        if ($request->hasFile('vc_image')) {
            if ($vc->vc_image) {
                Storage::delete('public/' . $vc->vc_image);
            }
            $upload = customUpload($request->file('vc_image'), 'homepage/vc');
            if ($upload['status'] === 0) {
                throw new \RuntimeException($upload['error_message']);
            }
            $vc->vc_image = $upload['file_path'];
        }

        $vc->save();
    }

    protected function updateExplore(Request $request): void
    {
        $explore = HomepageExplore::first() ?? new HomepageExplore();
        $explore->section_title = $request->explore_section_title;
        $explore->save();

        $items = $request->input('explore_items', []);
        $existingIds = [];
        $position = 1;

        foreach ($items as $item) {
            // skip completely empty rows
            if (trim(implode('', [
                $item['icon'] ?? '',
                $item['title'] ?? '',
                $item['url'] ?? '',
            ])) === '') {
                continue;
            }

            $id = $item['id'] ?? null;

            if ($id) {
                $box = HomepageExploreItem::where('explore_id', $explore->id)->find($id);
                if (!$box) {
                    $box = new HomepageExploreItem();
                    $box->explore_id = $explore->id;
                }
            } else {
                $box = new HomepageExploreItem();
                $box->explore_id = $explore->id;
            }

            $box->icon     = $item['icon']  ?? null;
            $box->title    = $item['title'] ?? null;
            $box->url      = $item['url']   ?? null;
            $box->position = $position++;
            $box->save();

            $existingIds[] = $box->id;
        }

        if (!empty($existingIds)) {
            HomepageExploreItem::where('explore_id', $explore->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        } else {
            HomepageExploreItem::where('explore_id', $explore->id)->delete();
        }
    }

    protected function updateFaculty(Request $request): void
    {
        $faculty = HomepageFaculty::first() ?? new HomepageFaculty();
        $faculty->section_title    = $request->faculty_section_title;
        $faculty->section_subtitle = $request->faculty_section_subtitle;
        $faculty->save();
    }

    protected function updateGlance(Request $request): void
    {
        $glance = HomepageGlance::first() ?? new HomepageGlance();
        $glance->section_title    = $request->glance_section_title;
        $glance->section_subtitle = $request->glance_section_subtitle;
        $glance->save();

        $items = $request->input('glance_items', []);
        $existingIds = [];
        $position = 1;

        foreach ($items as $item) {
            if (trim(implode('', [
                $item['icon'] ?? '',
                $item['title'] ?? '',
                $item['number'] ?? '',
            ])) === '') {
                continue;
            }

            $id = $item['id'] ?? null;

            if ($id) {
                $box = HomepageGlanceItem::where('glance_id', $glance->id)->find($id);
                if (!$box) {
                    $box = new HomepageGlanceItem();
                    $box->glance_id = $glance->id;
                }
            } else {
                $box = new HomepageGlanceItem();
                $box->glance_id = $glance->id;
            }

            $box->icon     = $item['icon']   ?? null;
            $box->title    = $item['title']  ?? null;
            $box->number   = $item['number'] ?? null;
            $box->position = $position++;
            $box->save();

            $existingIds[] = $box->id;
        }

        if (!empty($existingIds)) {
            HomepageGlanceItem::where('glance_id', $glance->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        } else {
            HomepageGlanceItem::where('glance_id', $glance->id)->delete();
        }
    }

    protected function updateAbout(Request $request): void
    {
        $about = HomepageAbout::first() ?? new HomepageAbout();

        $about->badge              = $request->about_badge;
        $about->title              = $request->about_title;
        $about->subtitle           = $request->about_subtitle;
        $about->description        = $request->about_description;
        $about->experience_badge   = $request->about_experience_badge;
        $about->experience_title   = $request->about_experience_title;

        $images = $about->images ?? [];
        if (!is_array($images)) {
            $images = [];
        }

        // We will store up to 5 images in $images[0..4]
        for ($i = 1; $i <= 5; $i++) {
            $removeField = "about_image_{$i}_remove";
            $fileField   = "about_image_{$i}";
            $index       = $i - 1;

            // Remove requested
            if ($request->boolean($removeField) && !empty($images[$index])) {
                Storage::delete('public/' . $images[$index]);
                $images[$index] = null;
            }

            if ($request->hasFile($fileField)) {
                if (!empty($images[$index])) {
                    Storage::delete('public/' . $images[$index]);
                }
                $upload = customUpload($request->file($fileField), 'homepage/about');
                if ($upload['status'] === 0) {
                    throw new \RuntimeException($upload['error_message']);
                }
                $images[$index] = $upload['file_path'];
            }
        }

        // normalize array (keep nulls; frontend can filter)
        $about->images = $images;

        $about->save();
    }
}
