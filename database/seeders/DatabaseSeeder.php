<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\CourseType;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::updateOrCreate(
            ['email' => env('USER1_EMAIL')],
            [
                'name' => env('USER1_NAME'),
                'email' => env('USER1_EMAIL'),
                'birthdate' => Carbon::parse(env('USER1_BIRTHDATE')),
                'password' => Hash::make(env('USER1_PASSWORD')),
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => env('USER2_EMAIL')],
            [
                'name' => env('USER2_NAME'),
                'email' => env('USER2_EMAIL'),
                'birthdate' => Carbon::parse(env('USER2_BIRTHDATE')),
                'password' => Hash::make(env('USER2_PASSWORD')),
                'js_number' => env('USER2_JS_NUMBER'),
            ]
        );

        $user3 = User::updateOrCreate(
            ['email' => env('USER3_EMAIL')],
            [
                'name' => env('USER3_NAME'),
                'email' => env('USER3_EMAIL'),
                'birthdate' => Carbon::parse(env('USER3_BIRTHDATE')),
                'password' => Hash::make(env('USER3_PASSWORD')),
                'js_number' => env('USER3_JS_NUMBER'),
                'is_js_coach' => true,
            ]
        );

        $user4 = User::updateOrCreate(
            ['email' => env('USER4_EMAIL')],
            [
                'name' => env('USER4_NAME'),
                'email' => env('USER4_EMAIL'),
                'birthdate' => Carbon::parse(env('USER4_BIRTHDATE')),
                'password' => Hash::make(env('USER4_PASSWORD')),
                'js_number' => env('USER4_JS_NUMBER'),
            ]
        );

        $team1 = Team::create([
            'name' => 'Kutu / Getu Knaben',
            'user_id' => $user1->id,
            'personal_team' => false,
        ]);

        $team2 = Team::create([
            'name' => 'Getu Mädchen',
            'user_id' => 1,
            'personal_team' => false,
        ]);

        $user1->teams()->attach($team1->id, ['role' => 'js_manager']);
        $user2->teams()->attach($team1->id, ['role' => 'coach']);
        $user3->teams()->attach($team1->id, ['role' => 'js_manager']);
        $user3->teams()->attach($team2->id, ['role' => 'js_manager']);
        $user4->teams()->attach($team1->id, ['role' => 'js_manager']);

        $user1->update(['current_team_id' => $team1->id]);
        $user2->update(['current_team_id' => $team1->id]);
        $user3->update(['current_team_id' => $team1->id]);
        $user4->update(['current_team_id' => $team1->id]);

        $courseType1 = CourseType::create([
            'name' => 'Grundkurs Kutu / Getu',
            'minimum_age' => 17,
            'requires_repetition' => 1,
            'is_kids_course' => 0,
            'can_only_attend_once' => 1,
            'order' => 1,
        ]);

        $courseType1->teams()->sync([$team1->id, $team2->id]);

        $courseType2 = CourseType::create([
            'name' => 'FK Kutu / Getu',
            'minimum_age' => 17,
            'order' => 2,
            'requires_repetition' => 1,
            'is_kids_course' => 0,
            'can_only_attend_once' => 0,
            'prerequisite_course_type_id' => $courseType1->id
        ]);

        $courseType2->teams()->sync([$team1->id, $team2->id]);

        $courseType3 = CourseType::create([
            'name' => '1418',
            'minimum_age' => 14,
            'maximum_age' => 18,
            'requires_repetition' => 0,
            'is_kids_course' => 0,
            'can_only_attend_once' => 1,
            'order' => 3,
        ]);

        $courseType3->teams()->sync([$team1->id, $team2->id]);

        $courseType4 = CourseType::create([
            'name' => 'Grundkurs / Umschulung Kids',
            'minimum_age' => 17,
            'requires_repetition' => 1,
            'is_kids_course' => 1,
            'can_only_attend_once' => 1,
            'order' => 4,
        ]);

        $courseType4->teams()->sync([$team1->id, $team2->id]);

        $courseType5 = CourseType::create([
            'name' => 'Aufbaukurs Kutu WB1',
            'minimum_age' => 17,
            'requires_repetition' => 1,
            'is_kids_course' => 0,
            'can_only_attend_once' => 0,
            'prerequisite_course_type_id' => $courseType1->id,
            'order' => 5,
        ]);

        $courseType5->teams()->sync([$team1->id, $team2->id]);

        $courseType6 = CourseType::create([
            'name' => 'Weiterbildungskurs Kutu WB1',
            'minimum_age' => 17,
            'order' => 6,
            'requires_repetition' => 1,
            'is_kids_course' => 0,
            'can_only_attend_once' => 0,
            'prerequisite_course_type_id' => $courseType5->id
        ]);

        $courseType6->teams()->sync([$team1->id, $team2->id]);

        $courseType7 = CourseType::create([
            'name' => 'FK Kids',
            'minimum_age' => 17,
            'order' => 7,
            'requires_repetition' => 1,
            'is_kids_course' => 1,
            'can_only_attend_once' => 0,
            'prerequisite_course_type_id' => $courseType4->id
        ]);

        $courseType7->teams()->sync([$team1->id, $team2->id]);

        Course::create([
            'course_nr' => 'SG 322-01.25',
            'name' => 'Kunstturnen',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2025-09-29'),
            'date_end' => Carbon::parse('2025-10-04'),
            'location' => 'Widnau',
            'registration_deadline' => Carbon::parse('2025-07-28'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/1660029174214464322/registration/-940765077809004820',
        ]);

        Course::create([
            'course_nr' => 'LU 2711-24',
            'name' => 'Grundkurs Getu',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2024-07-08'),
            'date_end' => Carbon::parse('2024-07-13'),
            'location' => 'Willisau',
            'registration_deadline' => Carbon::parse('2024-05-24'),
        ]);

        Course::create([
            'course_nr' => 'SG 322-01.24',
            'name' => 'Grundkurs Kutu',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2024-09-30'),
            'date_end' => Carbon::parse('2024-10-05'),
            'location' => 'Widnau',
            'registration_deadline' => Carbon::parse('2024-07-29'),
        ]);

        Course::create([
            'course_nr' => 'SG 322-01.22',
            'name' => 'Grundkurs Kutu',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2022-10-03'),
            'date_end' => Carbon::parse('2022-10-08'),
            'location' => 'Widnau',
            'registration_deadline' => Carbon::parse('2022-08-03'),
        ]);

        Course::create([
            'course_nr' => 'ZH 505.23',
            'name' => 'Grundkurs Kutu',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2023-07-23'),
            'date_end' => Carbon::parse('2023-07-28'),
            'location' => 'Zürich',
            'registration_deadline' => Carbon::parse('2023-02-08'),
        ]);

        Course::create([
            'course_nr' => 'AG 218.22',
            'name' => 'Fortbildung Leiter',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2022-10-23'),
            'date_end' => Carbon::parse('2022-10-23'),
            'location' => 'Siggenthal Station',
            'registration_deadline' => Carbon::parse('2022-08-23'),
        ]);

        Course::create([
            'course_nr' => 'SUS 23.3.J',
            'name' => 'Fortbildung Leiter',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2023-09-09'),
            'date_end' => Carbon::parse('2023-09-09'),
            'location' => 'Oberrohrdorf',
            'registration_deadline' => Carbon::parse('2023-07-09'),
        ]);

        Course::create([
            'course_nr' => 'AG 218.24',
            'name' => 'Fortbildung Leiter',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2024-10-27'),
            'date_end' => Carbon::parse('2024-10-27'),
            'location' => 'Oberentfelden',
            'registration_deadline' => Carbon::parse('2024-10-20'),
        ]);

        $fk1 = Course::create([
            'course_nr' => 'STV-69 1062574.3730',
            'name' => 'Fortbildung Leiter',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2024-02-18'),
            'date_end' => Carbon::parse('2024-02-18'),
            'location' => 'Lenzburg',
            'registration_deadline' => Carbon::parse('2024-01-15'),
        ]);

        Course::create([
            'course_nr' => 'STV-69 1062666.3790',
            'name' => 'Modul Fortbildung Leiter/in',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2025-07-06'),
            'date_end' => Carbon::parse('2025-07-06'),
            'location' => 'Rümlang',
            'notes' => 'Leitende Kunstturnen / Geräteturnen',
            'registration_deadline' => Carbon::parse('2025-05-04'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/1660029174214464322/registration/-8191319308079798802',
        ]);

        Course::create([
            'course_nr' => 'AG 218.25',
            'name' => 'Modul Fortbildung Leiter/in',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2025-10-26'),
            'date_end' => Carbon::parse('2025-10-26'),
            'location' => 'Oberentfelden',
            'notes' => 'Kunstturnen',
            'registration_deadline' => Carbon::parse('2025-10-17'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/1660029174214464322/registration/-8914028639951208737',
        ]);

        Course::create([
            'course_nr' => 'STV-69 1062691.3730',
            'name' => 'Modul Fortbildung Leiter/in',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2025-02-16'),
            'date_end' => Carbon::parse('2025-02-16'),
            'location' => 'Lenzburg',
            'notes' => 'Geräteturnen',
            'registration_deadline' => Carbon::parse('2024-12-15'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/-2574899887132409779/registration/-3108416648193008285',
        ]);

        Course::create([
            'course_nr' => 'BL 40-25',
            'name' => 'Modul Fortbildung Leiter/in',
            'course_type_id' => $courseType2->id,
            'date_start' => Carbon::parse('2025-03-22'),
            'date_end' => Carbon::parse('2025-03-22'),
            'location' => 'Liestal',
            'notes' => 'Geräteturnen',
            'registration_deadline' => Carbon::parse('2025-02-22'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/-2574899887132409779/registration/8575441586766211723',
        ]);

        Course::create([
            'course_nr' => 'AG 721a.25',
            'name' => 'EK Leiter',
            'course_type_id' => $courseType4->id,
            'date_start' => Carbon::parse('2025-11-08'),
            'date_end' => Carbon::parse('2025-11-09'),
            'location' => 'Windisch',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-10-31'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/3517218101747140187/registration/-8631718592812609253',
        ]);

        Course::create([
            'course_nr' => 'STV-69 2051138.3790',
            'name' => 'Aufbaukurs Kunstturnen',
            'course_type_id' => $courseType5->id,
            'date_start' => Carbon::parse('2025-12-08'),
            'date_end' => Carbon::parse('2025-12-13'),
            'location' => 'Magglingen',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-10-07'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/1660029174214464322/registration/-8595267594653608529',
        ]);

        Course::create([
            'course_nr' => 'STV-69 1062692.3730',
            'name' => 'Schwierige Elemente (K6 - K7)',
            'course_type_id' => $courseType6->id,
            'date_start' => Carbon::parse('2025-09-28'),
            'date_end' => Carbon::parse('2025-09-28'),
            'location' => 'Lenzburg',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-07-27'),
            'link' => 'https://www.nds.baspo.admin.ch/publicArea/cadreEducationStructure/cadre/education-structure/1660029174214464322/registration/-3106612781967443301',
        ]);

        Course::create([
            'name' => 'Turnen',
            'course_type_id' => $courseType3->id,
            'date_start' => Carbon::parse('2025-03-09'),
            'date_end' => Carbon::parse('2025-03-09'),
            'location' => 'Siggenthal Station',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-03-08'),
            'link' => 'https://www.ag.ch/de/verwaltung/bks/sport/1418coach/ausbildung#MjIzNTI3OQ',
        ]);

        Course::create([
            'name' => 'Geräte-/Kunstturnen',
            'course_type_id' => $courseType3->id,
            'date_start' => Carbon::parse('2025-04-05'),
            'date_end' => Carbon::parse('2025-04-05'),
            'location' => 'Lenzburg',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-04-04'),
            'link' => 'https://www.ag.ch/de/verwaltung/bks/sport/1418coach/ausbildung#MjIzNTI3OQ',
        ]);

        Course::create([
            'name' => 'Geräte-/Kunstturnen',
            'course_type_id' => $courseType3->id,
            'date_start' => Carbon::parse('2025-08-24'),
            'date_end' => Carbon::parse('2025-08-24'),
            'location' => 'Aarau',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-08-23'),
            'link' => 'https://www.ag.ch/de/verwaltung/bks/sport/1418coach/ausbildung#MjIzNTI3OQ',
        ]);

        Course::create([
            'name' => 'Geräte-/Kunstturnen',
            'course_type_id' => $courseType3->id,
            'date_start' => Carbon::parse('2025-11-15'),
            'date_end' => Carbon::parse('2025-11-15'),
            'location' => 'Siggenthal Station',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2025-11-14'),
            'link' => 'https://www.ag.ch/de/verwaltung/bks/sport/1418coach/ausbildung#MjIzNTI3OQ',
        ]);

        Course::create([
            'course_nr' => 'AG 721a.22',
            'name' => 'EK Kindersport',
            'course_type_id' => $courseType4->id,
            'date_start' => Carbon::parse('2022-11-22'),
            'date_end' => Carbon::parse('2022-11-23'),
            'location' => 'Siggenthal Station',
            'notes' => '',
            'registration_deadline' => Carbon::parse('2022-09-12'),
        ]);

        $oldKidskurs = Course::create([
            'course_nr' => '00-Kids',
            'name' => 'EK Kindersport / Grundkurs',
            'course_type_id' => $courseType4->id,
            'date_start' => Carbon::parse('2020-01-01'),
            'date_end' => Carbon::parse('2020-01-01'),
            'location' => '-',
            'is_hidden' => 1,
            'notes' => 'Dieser Kurs dient dazu, vorherige Kurse als besucht zu markieren.',
            'registration_deadline' => Carbon::parse('2019-12-31'),
        ]);

        $oldGrundkurs = Course::create([
            'course_nr' => '00-Grundkurs',
            'name' => 'Leiterkurs Kutu / Getu',
            'course_type_id' => $courseType1->id,
            'date_start' => Carbon::parse('2020-01-01'),
            'date_end' => Carbon::parse('2020-01-01'),
            'location' => '-',
            'is_hidden' => 1,
            'notes' => 'Dieser Kurs dient dazu, vorherige Kurse als besucht zu markieren.',
            'registration_deadline' => Carbon::parse('2019-12-31'),
        ]);

        $user2->courses()->attach($oldGrundkurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user2->courses()->attach($oldKidskurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user2->courses()->attach($fk1->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2024-02-18'),
        ]);

        $user3->courses()->attach($oldGrundkurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user3->courses()->attach($oldKidskurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user3->courses()->attach($fk1->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2024-02-18'),
        ]);

        $user4->courses()->attach($oldGrundkurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user4->courses()->attach($oldKidskurs->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2020-01-01'),
        ]);

        $user4->courses()->attach($fk1->id, [
            'status' => 'attended',
            'completed_at' => Carbon::parse('2024-02-18'),
        ]);

        // User::factory()->create([
        //     'name' => 'Nadim Salloum',
        //     'email' => 'test@example.com',
        // ]);
    }
}
