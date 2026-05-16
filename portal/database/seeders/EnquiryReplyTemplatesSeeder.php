<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EnquiryReplyTemplate;
use Illuminate\Database\Seeder;

class EnquiryReplyTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Initial Acknowledgement',
                'subject' => 'We have received your enquiry — Maruti Travels',
                'body_markdown' => "Dear {{customer_name}},\n\nThank you for reaching out to Maruti Travels. We have received your enquiry and our travel expert will get back to you within 24 hours.\n\nYour enquiry reference is: **{{enquiry_ref}}**\n\nBest regards,\nMaruti Travels Team",
                'is_active' => true,
            ],
            [
                'name' => 'Quotation Ready',
                'subject' => 'Your travel quotation is ready — Maruti Travels',
                'body_markdown' => "Dear {{customer_name}},\n\nWe are delighted to share your personalised travel quotation for **{{destination}}**.\n\nPlease find the quotation attached. The quotation is valid for **7 days** from the date of this email.\n\nIf you have any questions or need modifications, please don't hesitate to contact us.\n\nBest regards,\nMaruti Travels Team",
                'is_active' => true,
            ],
            [
                'name' => 'Follow Up',
                'subject' => 'Following up on your travel enquiry — Maruti Travels',
                'body_markdown' => "Dear {{customer_name}},\n\nWe hope you had a chance to review the information we shared earlier regarding your trip to **{{destination}}**.\n\nWe wanted to check in and see if you have any questions or if there is anything we can help clarify.\n\nOur offer remains valid and we would love to help make your travel dreams come true!\n\nBest regards,\nMaruti Travels Team",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EnquiryReplyTemplate::query()->firstOrCreate(
                ['name' => $template['name']],
                $template,
            );
        }
    }
}
