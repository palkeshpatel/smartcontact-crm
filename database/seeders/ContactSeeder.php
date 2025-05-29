<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'gender' => 'male',
                'custom_fields' => [
                    'Company' => 'Tech Corp',
                    'Birthday' => '1990-01-15',
                    'Address' => '123 Main St, City'
                ]
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1234567891',
                'gender' => 'female',
                'custom_fields' => [
                    'Company' => 'Design Studio',
                    'Birthday' => '1985-05-20',
                    'Position' => 'Senior Designer'
                ]
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'phone' => '+1234567892',
                'gender' => 'male',
                'custom_fields' => [
                    'Company' => 'Marketing Inc',
                    'Department' => 'Sales',
                    'Experience' => '5 years'
                ]
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'phone' => '+1234567893',
                'gender' => 'female',
                'custom_fields' => [
                    'Company' => 'Consulting Group',
                    'Birthday' => '1992-08-10',
                    'Specialty' => 'Business Analysis'
                ]
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'phone' => '+1234567894',
                'gender' => 'male',
                'custom_fields' => [
                    'Company' => 'Software Solutions',
                    'Position' => 'Lead Developer',
                    'Skills' => 'PHP, Laravel, JavaScript'
                ]
            ]
        ];

        foreach ($contacts as $contactData) {
            $customFields = $contactData['custom_fields'];
            unset($contactData['custom_fields']);

            $contact = Contact::create($contactData);

            foreach ($customFields as $fieldName => $fieldValue) {
                $contact->setCustomField($fieldName, $fieldValue);
            }
        }
    }
}
