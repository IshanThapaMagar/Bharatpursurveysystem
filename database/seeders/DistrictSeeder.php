<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['en' => 'Udayapur', 'np' => 'जिल्ला'],
            ['en' => 'Chitwan', 'np' => 'चितवन'],
            ['en' => 'Arghakhanchi', 'np' => 'अर्घाखाँची'],
            ['en' => 'Baglung', 'np' => 'बागलुङ'],
            ['en' => 'Baitadi', 'np' => 'बैतडी'],
            ['en' => 'Bajhang', 'np' => 'बझाङ'],
            ['en' => 'Bajura', 'np' => 'बाजुरा'],
            ['en' => 'Banke', 'np' => 'बाँके'],
            ['en' => 'Bara', 'np' => 'बारा'],
            ['en' => 'Bardiya', 'np' => 'बर्दिया'],
            ['en' => 'Bhaktapur', 'np' => 'भक्तपुर'],
            ['en' => 'Bhojpur', 'np' => 'भोजपुर'],
            ['en' => 'Chitwan', 'np' => 'अछाम'],
            ['en' => 'Dadeldhura', 'np' => 'डडेलधुरा'],
            ['en' => 'Dailekh', 'np' => 'दैलेख'],
            ['en' => 'Dang Deukhuri', 'np' => 'दाङ देउखुरी'],
            ['en' => 'Darchula', 'np' => 'दार्चुला'],
            ['en' => 'Dhading', 'np' => 'धादिङ'],
            ['en' => 'Dhankuta', 'np' => 'धनकुटा'],
            ['en' => 'Dhanusa', 'np' => 'धनुषा'],
            ['en' => 'Dolakha', 'np' => 'दोलखा'],
            ['en' => 'Dolpa', 'np' => 'डोल्पा'],
            ['en' => 'Doti', 'np' => 'डोटी'],
            ['en' => 'Gorkha', 'np' => 'गोरखा'],
            ['en' => 'Gulmi', 'np' => 'गुल्मी'],
            ['en' => 'Humla', 'np' => 'हुम्ला'],
            ['en' => 'Ilam', 'np' => 'इलाम'],
            ['en' => 'Jajarkot', 'np' => 'जाजरकोट'],
            ['en' => 'Jhapa', 'np' => 'झापा'],
            ['en' => 'Jumla', 'np' => 'जुम्ला'],
            ['en' => 'Kailali', 'np' => 'कैलाली'],
            ['en' => 'Kalikot', 'np' => 'कालिकोट'],
            ['en' => 'Kanchanpur', 'np' => 'कंचनपुर'],
            ['en' => 'Kapilvastu', 'np' => 'कपिलवस्तु'],
            ['en' => 'Kaski', 'np' => 'कास्की'],
            ['en' => 'Kathmandu', 'np' => 'काठमाडौँ'],
            ['en' => 'Kavrepalanchok', 'np' => 'काभ्रेकाभ्रेपलान्चोक'],
            ['en' => 'Khotang', 'np' => 'खोटाँग'],
            ['en' => 'Lalitpur', 'np' => 'ललितपुर'],
            ['en' => 'Lamjung', 'np' => 'लमजुङ'],
            ['en' => 'Mahottari', 'np' => 'महोत्तरी'],
            ['en' => 'Makwanpur', 'np' => 'मकवानपुर'],
            ['en' => 'Manang', 'np' => 'मनाङ'],
            ['en' => 'Morang', 'np' => 'मोरंग'],
            ['en' => 'Mugu', 'np' => 'मुगु'],
            ['en' => 'Mustang', 'np' => 'मुस्ताङ'],
            ['en' => 'Myagdi', 'np' => 'म्याग्दी'],
            ['en' => 'Nawalparasi East', 'np' => 'नवलपरासी पूर्व'],
            ['en' => 'Nawalparasi West', 'np' => 'नवलपरासी पश्चिम'],
            ['en' => 'Nuwakot', 'np' => 'नुवाकोट'],
            ['en' => 'Okhaldhunga', 'np' => 'ओखलढुंगा'],
            ['en' => 'Palpa', 'np' => 'पाल्पा'],
            ['en' => 'Panchthar', 'np' => 'पांचथर'],
            ['en' => 'Parbat', 'np' => 'पर्वत'],
            ['en' => 'Parsa', 'np' => 'पर्सा'],
            ['en' => 'Pyuthan', 'np' => 'प्युठान'],
            ['en' => 'Ramechhap', 'np' => 'रामेछाप'],
            ['en' => 'Rasuwa', 'np' => 'रसुवा'],
            ['en' => 'Rautahat', 'np' => 'रौतहट'],
            ['en' => 'Rolpa', 'np' => 'रोल्पा'],
            ['en' => 'Rukum East', 'np' => 'रूकुम पूर्वी'],
            ['en' => 'Rukum West', 'np' => 'रूकुम पश्चिम'],
            ['en' => 'Rupandehi', 'np' => 'रुपन्देही'],
            ['en' => 'Salyan', 'np' => 'सल्यान'],
            ['en' => 'Sankhuwasabha', 'np' => 'संखुवासभा'],
            ['en' => 'Saptari', 'np' => 'सप्तरी'],
            ['en' => 'Sarlahi', 'np' => 'सर्लाही'],
            ['en' => 'Sindhuli', 'np' => 'सिन्धुली'],
            ['en' => 'Sindhupalchok', 'np' => 'सिन्धुपाल्चोक'],
            ['en' => 'Siraha', 'np' => 'सिराहा'],
            ['en' => 'Solukhumbu', 'np' => 'सोलुखुम्बू'],
            ['en' => 'Sunsari', 'np' => 'सुनसरी'],
            ['en' => 'Surkhet', 'np' => 'सुर्खेत'],
            ['en' => 'Syangja', 'np' => 'स्याङग्जा'],
            ['en' => 'Tanahu', 'np' => 'तनहुँ'],
            ['en' => 'Taplejung', 'np' => 'ताप्लेजुंग'],
            ['en' => 'Tehrathum', 'np' => 'तेह्रथुम'],
            ['en' => 'Udayapur', 'np' => 'उदयपुर'],
        ];

        foreach ($districts as $item) {
            $district = District::create();
            $district->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}