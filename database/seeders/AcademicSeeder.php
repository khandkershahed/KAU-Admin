<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffSection;
use App\Models\AcademicStaffMember;
use App\Models\AcademicMemberPublication;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        /* -----------------------------------------------------------
         | UUID GENERATOR (6-8 chars, unique)
         | - Uses name + mobile(if exists) + phone(if exists) + counter
         | - Produces 8 chars by default; if collision, varies with counter
         |------------------------------------------------------------ */
        $makeUuid = function (string $name, ?string $mobile = null, ?string $phone = null): string {
            $base = trim($name);

            if (!empty($mobile)) {
                $base .= '|' . preg_replace('/\D+/', '', $mobile);
            } elseif (!empty($phone)) {
                $base .= '|' . preg_replace('/\D+/', '', $phone);
            } else {
                $base .= '|0';
            }

            // Try different lengths and counters to guarantee uniqueness
            $counter = 0;

            while (true) {
                $payload = $base . '|' . $counter;
                $hash = strtoupper(substr(sha1($payload), 0, 8)); // 8 chars

                // If you want sometimes 6-8 chars, you can vary by counter:
                // first attempt 8, then 7, then 6, then back to 8 with counter
                $len = 8;
                if ($counter === 1) $len = 7;
                if ($counter === 2) $len = 6;
                if ($counter >= 3) $len = 8;

                $uuid = substr($hash, 0, $len);

                if (!AcademicStaffMember::where('uuid', $uuid)->exists()) {
                    return $uuid;
                }

                $counter++;
            }
        };

        /* -----------------------------------------------------------
         | Publications helper (upsert by member_id + title)
         |------------------------------------------------------------ */
        $upsertPub = function (
            AcademicStaffMember $member,
            int $position,
            string $title,
            ?string $journal = null,
            ?int $year = null
        ) {
            AcademicMemberPublication::updateOrCreate(
                [
                    'academic_staff_member_id' => $member->id,
                    'title' => $title,
                ],
                [
                    'type' => null,
                    'journal_or_conference_name' => $journal,
                    'publisher' => null,
                    'year' => $year,
                    'doi' => null,
                    'url' => null,
                    'position' => $position,
                ]
            );
        };

        /* -----------------------------------------------------------
         | 1. MENU GROUPS
         |------------------------------------------------------------ */
        $facultyGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'faculty'],
            ['title' => 'Faculty', 'position' => 1, 'status' => 'published']
        );

        $instituteGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'institute'],
            ['title' => 'Institute', 'position' => 2, 'status' => 'published']
        );

        /* -----------------------------------------------------------
         | Helper: Create Site + Nav + Pages
         |------------------------------------------------------------ */
        $createSite = function (AcademicMenuGroup $group, array $siteData) {
            $site = AcademicSite::updateOrCreate(
                ['slug' => $siteData['slug']],
                [
                    'academic_menu_group_id' => $group->id,
                    'name'                   => $siteData['name'],
                    'short_name'             => $siteData['short_name'] ?? null,
                    'short_description'      => $siteData['short_description'] ?? null,
                    'theme_primary_color'    => $siteData['theme_primary_color'] ?? null,
                    'theme_secondary_color'  => $siteData['theme_secondary_color'] ?? null,
                    'logo_path'              => $siteData['logo_path'] ?? null,
                    'position'               => $siteData['position'] ?? 0,
                    'status'                 => 'published',
                ]
            );

            $rootNavs = [
                'home'            => ['Home', 'page'],
                'about'           => ['About ' . ($site->short_name ?? $site->name), 'page'],
                'departments'     => ['Departments', 'page'],
                'faculty_members' => ['Faculty Members', 'page'],
            ];

            $navItems = [];
            $position = 1;

            foreach ($rootNavs as $key => $item) {
                [$label, $type] = $item;

                $navItems[$key] = AcademicNavItem::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'menu_key'         => $key
                    ],
                    [
                        'parent_id'    => null,
                        'label'        => $label,
                        'slug'         => Str::slug($label),
                        'menu_key'     => $key,
                        'type'         => $type,
                        'external_url' => null,
                        'icon'         => null,
                        'position'     => $position++,
                        'status'       => 'published',
                    ]
                );
            }

            foreach ($navItems as $key => $nav) {
                AcademicPage::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'page_key'         => $key,
                    ],
                    [
                        'nav_item_id'         => $nav->id,
                        'slug'                => $nav->slug,
                        'title'               => $nav->label,
                        'is_home'             => $key === 'home',
                        'is_department_boxes' => $key === 'departments',
                        'is_faculty_members'  => $key === 'faculty_members',
                        'content'             => "<p>{$nav->label}</p>",
                        'status'              => 'published',
                        'position'            => $nav->position,
                    ]
                );
            }

            return $site;
        };

        /* -----------------------------------------------------------
         | CREATE SITES
         |------------------------------------------------------------ */
        $vabs = $createSite($facultyGroup, [
            'slug' => 'vabs',
            'name' => 'Veterinary, Animal and Biomedical Sciences',
            'short_name' => 'VABS',
            'position' => 1
        ]);

        $ag   = $createSite($facultyGroup, ['slug' => 'ag',   'name' => 'Agriculture', 'short_name' => 'AG', 'position' => 2]);
        $fos  = $createSite($facultyGroup, ['slug' => 'fos',  'name' => 'Fisheries & Ocean Sciences', 'short_name' => 'FOS', 'position' => 3]);
        $aeas = $createSite($facultyGroup, ['slug' => 'aeas', 'name' => 'Agricultural Economics & Agribusiness Studies', 'short_name' => 'AEAS', 'position' => 4]);
        $aet  = $createSite($facultyGroup, ['slug' => 'aet',  'name' => 'Agricultural Engineering & Technology', 'short_name' => 'AET', 'position' => 5]);

        /* ===========================================================
         | VABS — DEPARTMENTS
         =========================================================== */
        $vabsDepartments = [
            'Anatomy and Histology',
            'Physiology',
            'Pharmacology and Toxicology',
            'Microbiology and Public Health',
            'Livestock Production and Management',
            'Pathology',
            'Parasitology',
            'Genetics and Animal Breeding',
            'Dairy Science',
            'Poultry Science',
            'Epidemiology and Preventive Medicine',
            'Animal Nutrition',
            'Medicine',
            'Surgery',
            'Theriogenology',
        ];

        $deptMap = [];

        foreach ($vabsDepartments as $i => $deptTitle) {
            $deptMap[$deptTitle] = AcademicDepartment::updateOrCreate(
                [
                    'academic_site_id' => $vabs->id,
                    'slug' => Str::slug($deptTitle),
                ],
                [
                    'title' => $deptTitle,
                    'short_code' => strtoupper(substr(Str::slug($deptTitle), 0, 3)),
                    'status' => 'published',
                    'position' => $i + 1,
                ]
            );
        }

        /* ===========================================================
         | DEPARTMENT: Anatomy and Histology
         =========================================================== */
        $anatomy = $deptMap['Anatomy and Histology'];

        $anatomyHead = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $anatomy->id, 'title' => 'Head'],
            ['academic_site_id' => $vabs->id, 'position' => 1, 'status' => 'published']
        );

        $anatomyAsst = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $anatomy->id, 'title' => 'Assistant Professors'],
            ['academic_site_id' => $vabs->id, 'position' => 2, 'status' => 'published']
        );

        $anatomyLect = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $anatomy->id, 'title' => 'Lecturers'],
            ['academic_site_id' => $vabs->id, 'position' => 3, 'status' => 'published']
        );

        // Dr. Subarna Rani Kundu (Head)
        $subarnaPhone = '+8801756553339';
        $subarna = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $anatomyHead->id, 'name' => 'Dr. Subarna Rani Kundu'],
            [
                'uuid' => $makeUuid('Dr. Subarna Rani Kundu', null, $subarnaPhone),
                'designation' => 'Assistant Professor (Head)',
                'email' => 'srkundu@kau.edu.bd',
                'phone' => $subarnaPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        // Publications (Dr. Subarna Rani Kundu)
        $p = 1;
        $upsertPub($subarna, $p++, 'A comprehensive assessment of poultry husbandry practices at DK poultry farm, Chattogram, Bangladesh', 'International Journal of Natural and Social Sciences 12 (1), 44-51', 2025);
        $upsertPub($subarna, $p++, 'Hematological profile of indigenous sheep in Rajshahi Metropolitan area of Bangladesh', 'Bangladesh Journal of Agriculture and Life Science 2 (2), 55-62', 2024);
        $upsertPub($subarna, $p++, 'Diagnosis of Reproductive Disorders through Transrectal Ultrasonogram in Cows at Rajshahi District of Bangladesh', null, 2023);
        $upsertPub($subarna, $p++, 'Effects of nishyinda and papaya leaf extract on growth performance and hemato-biochemical parameters of broiler', 'International Journal of Applied Research 8 (02), 37-41', 2022);
        $upsertPub($subarna, $p++, 'Effect of Teaching Practical Anatomy to Increase the Knowledge and Skills of Veterinary Students in Bangladesh-A Sheep Model', 'International Journal of Innovation and Research in Educational Sciences 9', 2022);
        $upsertPub($subarna, $p++, 'A systematic review on brucellosis in Asia from 2000 to 2020', 'International Journal of Applied Research 8 (1), 40-43', 2022);
        $upsertPub($subarna, $p++, 'Pathological changes of liver and lung of slaughtered goats in Rajshahi Metropolitan area of Bangladesh', 'International Journal of Natural and Social Sciences 9 (01), 38-47', 2022);
        $upsertPub($subarna, $p++, 'Isolation, identification and antibiogram of bacterial flora from rectum of horses', 'GSC Biological and Pharmaceutical Sciences 21 (02), 116-126', 2022);

        // Papia Khatun
        $papiaPhone = '+8801756877950';
        $papia = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $anatomyAsst->id, 'name' => 'Papia Khatun'],
            [
                'uuid' => $makeUuid('Papia Khatun', null, $papiaPhone),
                'designation' => 'Assistant Professor',
                'email' => 'papiakhatun@kau.edu.bd',
                'phone' => $papiaPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        // Publications (Papia Khatun)
        $p = 1;
        $upsertPub($papia, $p++, 'Medicinal and versatile uses of an amazing, obtainable and valuable grass: Cynodon dactylon', 'International Journal of Pharmaceutical and Medicinal Research 8 (5), 1-11', 2020);
        $upsertPub($papia, $p++, 'Gross Anatomy of epididymis and ductus deferens of adult Khaki Campbell duck (Anas platyrhynchos domesticus) in Bangladesh', 'Journal of Bioscience and Agriculture Research 22 (01), 1805-1809', 2019);
        $upsertPub($papia, $p++, 'Histology of the male gonad of adult Khaki Campbell duck (Anas platyrhynchos domesticus) in Bangladesh', 'International Journal of Veterinary Sciences and Animal Husbandry 4 (4), 36-39', 2019);
        $upsertPub($papia, $p++, 'Microscopic features of gonadally inactive testis of khaki campbell duck (Anas platyrhynchos domesticus) in Bangladesh', 'Turkish Journal of Agriculture-Food Science and Technology 9 (1), 146-149', 2021);
        $upsertPub($papia, $p++, 'Gross morphology of testes and gonadosomatic index of Khaki Campbell duck (Anas platyrhynchos domesticus) at different postnatal ages', 'International Journal of Biology Medic 10 (2), 6694-6697', 2019);
        $upsertPub($papia, $p++, 'Gross Anatomical Features of Tongue of Khaki Campbell Duck (Anas platyrhynchos domesticus) At Different Postnatal Ages', 'Ukrainian Journal of Veterinary and Agricultural Sciences 5 (1), 17-23', 2022);
        $upsertPub($papia, $p++, 'Histological examination of testicular cell development in khaki Campbell ducklings (Anas Platyrhynchos Domesticus)', 'Int. J. Biol. Res. 4, 55-57', 2019);
        $upsertPub($papia, $p++, 'Effects of Low‐Dose Cypermethrin Exposure on the Liver and Kidney of Swiss Albino Mice: Histopathological and Biochemical Insights', 'Veterinary Medicine and Science 12 (1), e70706', 2026);
        $upsertPub($papia, $p++, 'Comparative Effects of Vitamin D3 and Sunlight on Ameliorating High‐Fat Diet–Induced Obesity in Mice', 'Journal of Food Biochemistry 2025 (1), 4930890', 2025);
        $upsertPub($papia, $p++, 'Ukrainian Journal of Veterinary and Agricultural Sciences', 'Ukrainian Journal of Veterinary and Agricultural Sciences', 2022);

        // Dr. Swarup Kumar Kundu
        $swarupPhone = '+8801714871808';
        $swarup = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $anatomyAsst->id, 'name' => 'Dr. Swarup Kumar Kundu'],
            [
                'uuid' => $makeUuid('Dr. Swarup Kumar Kundu', null, $swarupPhone),
                'designation' => 'Assistant Professor',
                'email' => 'swarupkundu@kau.edu.bd',
                'phone' => $swarupPhone,
                'status' => 'published',
                'position' => 2,
            ]
        );

        // Publications (Dr. Swarup Kumar Kundu)
        $p = 1;
        $upsertPub($swarup, $p++, 'Prevalence of some common bacterial diseases in commercial poultry farm', 'Ukrainian journal of veterinary and agricultural sciences 4 (2), 44-51', 2021);
        $upsertPub($swarup, $p++, 'Preparation of Quail (Coturnix coturnix) Skeleton to Promote the Teaching Facilities of Avian Anatomy Laboratory', 'International Journal of Veterinary and Animal Research (IJVAR) 6 (3), 91-95', 2023);
        $upsertPub($swarup, $p++, 'Cow Brain Consumption Causes Hypercholesterolemia: An in Vivo Study.', 'Asian Journal of Dairy & Food Research 40 (2)', 2021);
        $upsertPub($swarup, $p++, 'Excessive Green Tea Intake Alters Hemoglobin (Hb) Concentration and Histoarchitecture of Liver', 'Turkish Journal of Agriculture-Food Science and Technology 10 (8), 1404-1409', 2022);
        $upsertPub($swarup, $p++, 'Beneficial Role of Mushroom in Recovering Complications of Hypercholesterolemia', 'Indonesian Journal of Pharmaceutical and Clinical Research 4 (2), 1-14', 2021);
        $upsertPub($swarup, $p++, 'Green Tea: Conventional Facts and its Frontier Prospect on Health-A review', 'Turkish Journal of Agriculture-Food Science and Technology 9 (6), 1222-1225', 2021);
        $upsertPub($swarup, $p++, 'Biochemical and cellular (liver and kidney) restorative properties of garlic (Allium sativum) aqueous extract in cow brain-induced hypercholesterolemic model Swiss albino mice', 'European Journal of Clinical and Experimental Medicine 21 (3), 450-457', 2023);
        $upsertPub($swarup, $p++, 'Postnatal development of duodenum in broiler', 'Journal of Istanbul Veterinary Sciences 5 (2), 113-116', 2021);
        $upsertPub($swarup, $p++, 'Effects of Low-Dose Cypermethrin Exposure on the Liver and Kidney of Swiss Albino Mice: Histopathological and Biochemical Insights', 'Veterinary Medicine and Science 12 (1), 1-10', 2025);
        $upsertPub($swarup, $p++, 'Comparative Effects of Vitamin D3 and Sunlight on Ameliorating High‐Fat Diet–Induced Obesity in Mice', 'Journal of Food Biochemistry', 2025);
        $upsertPub($swarup, $p++, 'Potential Renal Effects of Cigarette Smoking in the Diabetic State-A review', 'Turkish Journal of Agriculture - Food Science and Technology 11 (12), 2481-2484', 2023);
        $upsertPub($swarup, $p++, 'Comparative study on food safety knowledge, attitude and practice among street food vendors and consumers', 'International Journal of Natural Sciences 11 (2), 40-60', 2021);
        $upsertPub($swarup, $p++, 'Prevalence and death rate of COVID-19 in some selected countries and its socio-economic impacts in Khulna division of Bangladesh', 'International Journal of Community Medicine and Public Health 8 (5), 2116', 2021);
        $upsertPub($swarup, $p++, 'Green Tea Departs Baleful Effects on Health in Swiss Albino Mice', 'PROCEEDINGS OF INTERNATIONAL SYMPOSIUM ON A NEW ERA IN FOOD SCIENCE AND …', 2019);

        /* ===========================================================
         | DEPARTMENT: Animal Nutrition
         =========================================================== */
        $nutrition = $deptMap['Animal Nutrition'];

        $nutritionHead = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $nutrition->id, 'title' => 'Head'],
            ['academic_site_id' => $vabs->id, 'position' => 1, 'status' => 'published']
        );

        $nutritionAsst = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $nutrition->id, 'title' => 'Assistant Professors'],
            ['academic_site_id' => $vabs->id, 'position' => 2, 'status' => 'published']
        );

        // Dr. Sabuj Kanti Nath
        $sabujPhone = '+8801851955071';
        $sabuj = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $nutritionHead->id, 'name' => 'Dr. Sabuj Kanti Nath'],
            [
                'uuid' => $makeUuid('Dr. Sabuj Kanti Nath', null, $sabujPhone),
                'designation' => 'Assistant Professor',
                'email' => 'sabuj.vet@kau.edu.bd',
                'phone' => $sabujPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        // Publications (Dr. Sabuj Kanti Nath)
        $p = 1;
        $upsertPub($sabuj, $p++, 'Topographical and biometrical anatomy of the digestive tract of White New Zealand Rabbit (Oryctolagus cuniculus)', 'Journal of Advanced Veterinary and Animal Research 3 (2), 145-151', 2016);
        $upsertPub($sabuj, $p++, 'Isolation and identification of Escherichia coli and Salmonella sp. from apparently healthy Turkey', 'Int. J. Adv. Res. Biol. Sci 4 (6), 72-78', 2017);
        $upsertPub($sabuj, $p++, 'Prevalence of gastrointestinal parasitism of cattle at Chandaniash Upazilla, Chittagong, Bangladesh', 'Int. J. Adv. Res. Biol. Sci 4 (6), 144-149', 2017);
        $upsertPub($sabuj, $p++, 'Prevalence of clinical conditions in dogs and cats at central veterinary hospital (CVH) in Dhaka, Bangladesh', 'Van Veterinary Journal 26 (2), 101-105', 2015);
        $upsertPub($sabuj, $p++, 'Nutritive value of rubber seed (Hevea brasiliensis)', 'Online Journal of Animal and Feed Research 5 (1), 18-21', 2015);
        $upsertPub($sabuj, $p++, 'Effects of antibiotic, acidifier, and probiotic supplementation on mortality rates, lipoprotein profile, and carcass traits of broiler chickens', 'Veterinary and Animal Science 22, 100325', 2023);
        $upsertPub($sabuj, $p++, 'Prevalence of some common bacterial diseases in commercial poultry farm', 'Ukrainian journal of veterinary and agricultural sciences 4 (2), 44-51', 2021);
        $upsertPub($sabuj, $p++, 'Management, growth performance and cost effectiveness of Japanese Quail in Khaza Quail Farm and Hatchery limited at Chittagong in Bangladesh', 'Global Journal of Medical Research 17 (1)', 2017);
        $upsertPub($sabuj, $p++, 'Isolation of Escherichia coli from the liver and yolk sac of day-old chicks with their antibiogram', 'Br J Biomed Multidiscip Res 1 (1), 19-25', 2017);
        $upsertPub($sabuj, $p++, 'Productive and Reproductive Performance of Red Chittagong Cattle (Rcc) In Rural Rearing System of Bangladesh', 'Asian Journal of Science and Technology 7 (07), 3152-3156', 2016);
        $upsertPub($sabuj, $p++, 'Production performance of different cross breeds of milch cow in Mithapukur Upazila, Rangpur, Bangladesh', 'International Journal of Advanced Multidisciplinary Research 3 (6), 29-33', 2016);
        $upsertPub($sabuj, $p++, 'Prevalence of gastrointestinal helminthiasis in naturally infested buffalo in Sylhet district', 'Int. J. Adv. Multidiscipl. Res 3, 51-28', 2016);
        $upsertPub($sabuj, $p++, 'Multi-drug resistance pattern of Escherichia coli isolated from hospital effluent and determination of tetracycline resistance gene', 'J. Inf. Mol. Biol 4 (3), 49-53', 2017);
        $upsertPub($sabuj, $p++, 'Prevalence and Antibiogram of Salmonella in Hisex Brown Strain at Commercial Poultry Farm in Chittagong', 'Int. J. Curr. Res. Biol. Med 2 (3), 14-19', 2015);
        $upsertPub($sabuj, $p++, 'Advancement of animal and poultry nutrition: Harnessing the power of CRISPR-Cas genome editing technology', 'Journal of Advanced Veterinary and Animal Research 11 (2), 483', 2024);
        $upsertPub($sabuj, $p++, 'Umbilical hernia in calves in Sylhet region', 'International Journal of Advanced Multidisciplinary Research 3 (7), 19-25', 2016);
        $upsertPub($sabuj, $p++, 'Fecal hormone assay and urinalysis of pregnant cattle', 'Adv. Anim. Vet. Sci 4 (4), 200-204', 2016);
        $upsertPub($sabuj, $p++, 'Haematobiochemical aspects of Foot and Mouth disease in cattle in Chittagong', 'Bangladesh. J. Inf. Mol. Biol 3 (3), 62-65', 2015);
        $upsertPub($sabuj, $p++, 'Trained immunity: a revolutionary immunotherapeutic approach', 'Animal Diseases 4 (1), 31', 2024);
        $upsertPub($sabuj, $p++, 'Prevalence and Antimicrobial Resistance Profile of E. coli and Salmonella spp. from Liver and Heart of Chickens', 'Turkish Journal of Agriculture-Food Science and Technology 10 (6), 1191-1196', 2022);

        // Dr. Md. Taslim Hossain
        $taslimPhone = '+8801711275811';
        $taslim = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $nutritionAsst->id, 'name' => 'Dr. Md. Taslim Hossain'],
            [
                'uuid' => $makeUuid('Dr. Md. Taslim Hossain', null, $taslimPhone),
                'designation' => 'Assistant Professor',
                'email' => 'drtaslim2178@gmail.com',
                'phone' => $taslimPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        // Publications (Dr. Md. Taslim Hossain)
        $p = 1;
        $upsertPub($taslim, $p++, 'Isolation, identification, toxin profile and antibiogram of Escherichia coli isolated from broilers and layers in Mymensingh district of Bangladesh', 'Bangladesh Journal of Veterinary Medicine 6 (1), 1-5', 2008);
        $upsertPub($taslim, $p++, 'Prevalence and economic significance of caprine fascioliasis at Sylhet District of Bangladesh.', null, 2011);
        $upsertPub($taslim, $p++, 'Effect of different carbon sources on in vitro regeneration of Indian pennywort (Centella asiatica L.).', null, 2005);
        $upsertPub($taslim, $p++, 'A report on problems and prospects of duck rearing system at Jaintiapur Upazila, Sylhet, Bangladesh', 'Journal of Global Agriculture and Ecology 11 (2), 25-35', 2021);
        $upsertPub($taslim, $p++, 'Prevalence and risk factors of soil transmitted helminths (STHs) infection among tea garden community in Sylhet and slum dwellers of Dhaka city, Bangladesh', 'MS, Dissertation, Sylhet Agricultural University, Sylhet', 2015);
        $upsertPub($taslim, $p++, 'Antibiogram profile of Escherichia coli isolated from migratory birds', 'Eurasian Journal of Veterinary Sciences 27 (3), 167-170', 2011);
        $upsertPub($taslim, $p++, 'Characterization of Escherichia coli isolated from migratory water fowls in Hakaluki Haor, Bangladesh', 'Glob. J. Med. Public Heal 1, 30-34', 2012);
        $upsertPub($taslim, $p++, 'Control of Blood Glucose in Type 2 Diabetes by Modification of Conventional Diet Composition.', 'American Journal of Food and Nutrition 7 (3), 72-77', 2019);
        $upsertPub($taslim, $p++, 'Human Nutritional Status and Environmental Conditions of Rice Mill Area at Alamdanga of Chuadanga.', 'Bangladesh Journal of Environmental Science 11 (2), 471-475', 2005);
        $upsertPub($taslim, $p++, 'A Report on Production and Management System of Broiler at Shibpur Upazila, Narsingdi, Bangladesh', 'Asian Journal of Research in Animal and Veterinary Sciences 7 (3), 20-33', 2021);
        $upsertPub($taslim, $p++, 'Coproscopic and Slaughter House Study of Paramphistomiasis in Cattle at Sylhet District of Bangladesh.', 'The Journal of Advances in Parasitology 6 (2), 1-6', 2019);
        $upsertPub($taslim, $p++, 'Effect of Nutrition (Urea-Molasses-Mineral Block) on the Age at Puberty of Zebu Heifers', 'Bangladesh Journal of Environmental Science 16 (2), 20-23', 2009);
        $upsertPub($taslim, $p++, 'Deterioration of Water Quality at Mymensingh Municipality.', 'Bangladesh Journal of Environmental Science 12 (1), 135-138', 2006);
        $upsertPub($taslim, $p++, 'Effect of Saponin and L-carnitine on the Performance of Female Broiler Chicken.', 'Bangladesh Journal of Environmental Science 11 (2), 419-424', 2005);
        $upsertPub($taslim, $p++, 'Global Journal of Medical and Public Health', 'Global Journal of Medical and Public Health', null);

        // Dr. Shahabuddin Ahmed
        $shahabPhone = '+8801742975056';
        $shahab = AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $nutritionAsst->id, 'name' => 'Dr. Shahabuddin Ahmed'],
            [
                'uuid' => $makeUuid('Dr. Shahabuddin Ahmed', null, $shahabPhone),
                'designation' => 'Assistant Professor',
                'email' => 'drshahab@kau.edu.bd',
                'phone' => $shahabPhone,
                'status' => 'published',
                'position' => 2,
            ]
        );

        // Publications (Dr. Shahabuddin Ahmed)
        $p = 1;
        $upsertPub($shahab, $p++, 'Productive and Reproductive Performance of Different Crossbred Dairy Cattle at Kishoreganj, Bangladesh', 'Veterinary Sciences: Research and Reviews 7 (1), 69-76', 2021);
        $upsertPub($shahab, $p++, 'A report on problems and prospects of duck rearing system at Jaintiapur Upazila, Sylhet, Bangladesh', 'Journal of Global Agriculture and Ecology 11 (2), 25-35', 2021);
        $upsertPub($shahab, $p++, 'Effect of Graded Levels of Slaughter House Residues on Growth Performance and Haematological Parameters in Broiler Chicken’s Ration', 'Asian Research Journal of Agriculture 9 (1), 1-8', 2018);
        $upsertPub($shahab, $p++, 'A comprehensive review on basil seeds as a source of nutrients and functional ingredients with health benefit properties', 'Applied Food Research 5 (1), 19', 2025);
        $upsertPub($shahab, $p++, 'Nutritional Evaluation of Cassava Meal Components and Maize in Securing Feed and Food', 'ANGIOTHERAPY 8 (3), 1-7', 2024);
        $upsertPub($shahab, $p++, 'Clinical Prevalence and Influencing Factors Analysis for the Occurrence of Peste Des Petits Ruminants (PPR) Disease of Goat at Sylhet Region, Bangladesh', null, 2017);
        $upsertPub($shahab, $p++, 'Prevalence of Various Clinical Diseases and Disorders in Goats at Kasba Upazilla, Bangladesh', 'Agricultural Science Digest-A Research Journal 42 (4), 482-487', 2022);
        $upsertPub($shahab, $p++, 'Patho-physiological investigation of anorexia of cattle at Sylhet district in Bangladesh', 'International Journal of Natural Sciences 5 (2), 90-92', 2015);
        $upsertPub($shahab, $p++, 'Effect of Cassava (Manihot esculenta Crantz) Leaf Meal as Partial Substitute for Soybean Meal on Growth Performance, Blood Profile and Meat Quality of Broilers', 'Asian Journal of Dairy and Food Research, 1-8', 2025);
        $upsertPub($shahab, $p++, 'A Review on Biodegradable Films from Banana Peel', 'Asian Food Science Journal 23 (12), 33-46', 2024);
        $upsertPub($shahab, $p++, 'Gingeer and Kalojeera as a growth promoter to broilers and its effect on hematological parameter', 'Veterinary Sciences: Research and Reviews 8 (2), 74-80', 2022);
        $upsertPub($shahab, $p++, 'A Report on Production and Management System of Broiler at Shibpur Upazila, Narsingdi, Bangladesh', 'Asian Journal of Research in Animal and Veterinary Sciences, 20-33', 2021);

        /* ===========================================================
         | Extra VABS departments staff (no publications provided)
         =========================================================== */

        // Department of Medicine
        $medicine = $deptMap['Medicine'];
        $medicineLect = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $medicine->id, 'title' => 'Lecturers'],
            ['academic_site_id' => $vabs->id, 'position' => 1, 'status' => 'published']
        );

        $solamaPhone = '+8801729625389';
        AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $medicineLect->id, 'name' => 'Dr. Solama Akter Shanta'],
            [
                'uuid' => $makeUuid('Dr. Solama Akter Shanta', null, $solamaPhone),
                'designation' => 'Lecturer',
                'email' => 'shanta.dvm@gmail.com',
                'phone' => $solamaPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        // Department of Livestock Production and Management
        $lpm = $deptMap['Livestock Production and Management'];

        $lpmHead = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $lpm->id, 'title' => 'Head'],
            ['academic_site_id' => $vabs->id, 'position' => 1, 'status' => 'published']
        );

        $lpmLect = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $lpm->id, 'title' => 'Lecturers'],
            ['academic_site_id' => $vabs->id, 'position' => 2, 'status' => 'published']
        );

        $uzzalPhone = '+8801737345773';
        AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $lpmHead->id, 'name' => 'Md. Uzzal Hossain'],
            [
                'uuid' => $makeUuid('Md. Uzzal Hossain', null, $uzzalPhone),
                'designation' => 'Lecturer (Head)',
                'email' => 'uzzallpm@kau.edu.bd',
                'phone' => $uzzalPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        $lpmLectMembers = [
            ['name' => 'Dr. Mustasim Famous', 'designation' => 'Assistant Professor', 'phone' => '+8801626312484', 'email' => 'mustasim@kau.edu.bd'],
            ['name' => 'Dr. Md. Ahsan Habib', 'designation' => 'Lecturer', 'phone' => '+8801723184396', 'email' => 'ahsanhabib96@kau.edu.bd'],
            ['name' => 'Dr. Fowzia Bahar', 'designation' => 'Lecturer', 'phone' => '+8801792571345', 'email' => 'fowzia.kau@gmail.com'],
            ['name' => 'Dr. Tanzila Zafrin Tanvi', 'designation' => 'Lecturer', 'phone' => '+8801677690041', 'email' => 'zaf.zila123@gmail.com'],
        ];

        $pos = 1;
        foreach ($lpmLectMembers as $m) {
            AcademicStaffMember::updateOrCreate(
                ['staff_section_id' => $lpmLect->id, 'name' => $m['name']],
                [
                    'uuid' => $makeUuid($m['name'], null, $m['phone'] ?? null),
                    'designation' => $m['designation'],
                    'email' => $m['email'],
                    'phone' => $m['phone'],
                    'status' => 'published',
                    'position' => $pos++,
                ]
            );
        }

        // Department of Microbiology and Public Health
        $mph = $deptMap['Microbiology and Public Health'];

        $mphHead = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $mph->id, 'title' => 'Head'],
            ['academic_site_id' => $vabs->id, 'position' => 1, 'status' => 'published']
        );

        $mphAsst = AcademicStaffSection::updateOrCreate(
            ['academic_department_id' => $mph->id, 'title' => 'Assistant Professors'],
            ['academic_site_id' => $vabs->id, 'position' => 2, 'status' => 'published']
        );

        $salauddinPhone = '+8801767178610';
        AcademicStaffMember::updateOrCreate(
            ['staff_section_id' => $mphHead->id, 'name' => 'Dr. Md. Salauddin'],
            [
                'uuid' => $makeUuid('Dr. Md. Salauddin', null, $salauddinPhone),
                'designation' => 'Assistant Professor (Head)',
                'email' => 'salauddin.dvm@gmail.com',
                'phone' => $salauddinPhone,
                'status' => 'published',
                'position' => 1,
            ]
        );

        $mphMembers = [
            ['name' => 'Dr. Nahid Rahman', 'designation' => 'Assistant Professor (On Leave)', 'phone' => '+8801714069315', 'email' => 'nahid.rahman85@gmail.com'],
            ['name' => 'Dr. Md. Jannat Hossain', 'designation' => 'Assistant Professor (On Leave)', 'phone' => '+8801723120404', 'email' => 'jannat@kau.edu.bd'],
            ['name' => 'Dr. Muhammad Sohidullah', 'designation' => 'Assistant Professor', 'phone' => '+8801736677924', 'email' => 'sohidullah@kau.edu.bd'],
            ['name' => 'Dr. Bidyut Matubber', 'designation' => 'Assistant Professor', 'phone' => '+8801755276696', 'email' => 'bidyutm78@kau.edu.bd'],
            ['name' => 'Dr. Muhammad Ashiqul Alam', 'designation' => 'Assistant Professor', 'phone' => '+8801712053192', 'email' => 'ashiq.alam@gmail.com'],
        ];

        $pos = 1;
        foreach ($mphMembers as $m) {
            AcademicStaffMember::updateOrCreate(
                ['staff_section_id' => $mphAsst->id, 'name' => $m['name']],
                [
                    'uuid' => $makeUuid($m['name'], null, $m['phone'] ?? null),
                    'designation' => $m['designation'],
                    'email' => $m['email'],
                    'phone' => $m['phone'] ?? null,
                    'status' => 'published',
                    'position' => $pos++,
                ]
            );
        }

        /* ===========================================================
         | OTHER FACULTIES — DEPARTMENTS ONLY (NO OBJECT KEYS)
         =========================================================== */
        $otherFaculties = [
            [
                'site' => $ag,
                'departments' => [
                    'Agronomy',
                    'Soil Science',
                    'Horticulture',
                    'Crop Botany',
                    'Agricultural Chemistry',
                    'Biochemistry and Molecular Biology',
                    'Entomology',
                    'Plant Pathology',
                    'Genetics and Plant Breeding',
                    'Agricultural Extension and Information Systems',
                    'Agroforestry',
                ],
            ],
            [
                'site' => $fos,
                'departments' => [
                    'Fishery Biology and Genetics',
                    'Aquaculture',
                    'Fishery Resources Conservation and Management',
                    'Oceanography',
                    'Fisheries Technology and Quality Control',
                    'Fish Health Management',
                ],
            ],
            [
                'site' => $aeas,
                'departments' => [
                    'Agricultural Economics',
                    'Sociology and Rural Development',
                    'Agricultural Statistics',
                    'Language and Communication Studies',
                    'Agricultural Finance, Co-operative and Banking',
                    'Agribusiness and Marketing',
                ],
            ],
            [
                'site' => $aet,
                'departments' => [
                    'Farm Structure',
                    'Farm Power and Machinery',
                    'Mathematics and Physics',
                    'Computer Science and Engineering',
                    'Irrigation and Water Management',
                ],
            ],
        ];

        foreach ($otherFaculties as $row) {
            $site = $row['site'];
            $departments = $row['departments'];

            foreach ($departments as $i => $title) {
                AcademicDepartment::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'slug' => Str::slug($title),
                    ],
                    [
                        'title' => $title,
                        'status' => 'published',
                        'position' => $i + 1,
                    ]
                );
            }
        }
    }
}
