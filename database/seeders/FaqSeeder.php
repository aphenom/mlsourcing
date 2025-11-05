<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;


class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::create([
            'question' => 'How can I submit a product request?',
            'answer' => 'To submit a product request, log in to your seller account, go to the "Requests" section, and click "New Request". You will need to fill out a form with the details of the product you wish to import. Once submitted, your request will be reviewed by an agent to confirm product availability.'
        ]);

        Faq::create([
            'question' => 'What is the payment process for requests?',
            'answer' => 'Once your product request is approved, you will need to make a payment to cover the import fees. Payments can be made through the available payment methods on the platform. After payment, an agent will be assigned to your request to proceed with product sourcing and shipment.'
        ]);

        Faq::create([
            'question' => 'How can I track the status of my request?',
            'answer' => 'You can track the status of your request by going to the "My Requests" section. The status of each request will be displayed (e.g., pending, in progress, shipped). You will also receive notifications when the status of your request changes.'
        ]);

        Faq::create([
            'question' => 'What if my payment is rejected?',
            'answer' => 'If your payment is rejected by the administrator, you will receive a notification explaining the reason. You may need to update your payment information or resolve any payment-related issues before you can submit a new request.'
        ]);

        Faq::create([
            'question' => 'How do agents find products for sellers’ requests?',
            'answer' => 'Agents are assigned to specific product requests and are responsible for sourcing the requested products. They check suppliers, obtain quotes, and ship the products once the payment is approved. Agents receive notifications when a new request is assigned to them.'
        ]);

        Faq::create([
            'question' => 'What information should be included in my request?',
            'answer' => 'Your request should include the following information: product name, quantity, specifications (size, color, etc.), desired country of origin, and any other relevant details. The more precise the details, the easier it will be for an agent to find the product.'
        ]);

        Faq::create([
            'question' => 'How can I communicate with an agent or seller?',
            'answer' => 'The platform allows sellers and agents to communicate via an integrated messaging system. You can ask questions or provide additional information about a request through the messaging interface available in the "Requests" section.'
        ]);

        Faq::create([
            'question' => 'What if I encounter an issue with the shipping of my product?',
            'answer' => 'If you experience any issues with the shipment, you can submit a reclamation through the reclamation form in your seller account. This will notify the administrator, who will investigate and resolve the issue.'
        ]);

        Faq::create([
            'question' => 'How can I request a refund if my product arrives damaged?',
            'answer' => 'If your product arrives damaged, you can submit a reclamation by selecting "Order" as the reclamation type. You will need to provide evidence (photos, documents) to support your request for a refund or replacement.'
        ]);

        Faq::create([
            'question' => 'What should I do if I want to modify or cancel my request?',
            'answer' => 'Once a request is submitted, it cannot be directly modified. However, you can contact an administrator through the platform’s messaging system to request a modification or cancellation before the agent begins processing the request.'
        ]);

        Faq::create([
            'question' => 'How can I track pending payments and orders?',
            'answer' => 'Sellers can track their payments and orders by accessing their dashboard. Each payment is linked to a specific request, and the order status (pending, in progress, shipped) is updated based on the agent’s processing.'
        ]);

        Faq::create([
            'question' => 'Who approves my payments on the platform?',
            'answer' => 'Payments are approved by the platform administrator. The administrator verifies the payments to ensure they are complete and compliant before approving the shipping process to be initiated by the agent.'
        ]);

    }
}
