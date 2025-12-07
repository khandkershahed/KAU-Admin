<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    protected $fillable = [
        'hero1_title','hero1_subtitle','hero1_button_text','hero1_button_url','hero1_image_path',
        'hero2_title','hero2_subtitle','hero2_button_text','hero2_button_url','hero2_image_path',
        'hero3_title','hero3_subtitle','hero3_button_text','hero3_button_url','hero3_image_path',

        'vc_section_title','vc_section_button_text','vc_section_button_url',
        'vc_message','vc_name','vc_designation','vc_photo_path',

        'explore_section_title','explore_section_subtitle','explore_boxes',

        'faculty_title','faculty_subtitle',

        'at_a_glance_title','at_a_glance_subtitle','at_a_glance_boxes',

        'about_badge','about_title','about_subtitle','about_description',
        'about_experience_badge','about_experience_title','about_section_images',

        'important_links_title','important_links_description',

        'section_layout',
    ];

    protected $casts = [
        'explore_boxes'        => 'array',
        'at_a_glance_boxes'    => 'array',
        'about_section_images' => 'array',
        'section_layout'       => 'array',
    ];
}
