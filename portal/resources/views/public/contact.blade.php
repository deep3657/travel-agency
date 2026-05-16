<x-public-layout title="Contact Us">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
        <p class="text-gray-500 mb-8">We'd love to help you plan your next adventure.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-xl font-semibold mb-4">Get in Touch</h2>
                <div class="space-y-4 text-gray-600">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📧</span>
                        <div>
                            <div class="font-medium">Email</div>
                            <div>info@marutitravels.in</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📞</span>
                        <div>
                            <div class="font-medium">Phone</div>
                            <div>+91 98765 43210</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🕐</span>
                        <div>
                            <div class="font-medium">Hours</div>
                            <div>Mon–Sat: 9:00 AM – 7:00 PM</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📍</span>
                        <div>
                            <div class="font-medium">Address</div>
                            <div>123 Travel Street, Andheri West<br>Mumbai, Maharashtra 400053</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4">Send a Message</h2>
                <p class="text-gray-600 text-sm mb-4">Or <a href="{{ route('customer.register') }}" class="text-[#0F4C81] hover:underline">create an account</a> to submit a detailed travel enquiry and get a personalised quotation.</p>
                <div class="space-y-3">
                    <input type="text" placeholder="Your Name" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]">
                    <input type="email" placeholder="Email Address" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]">
                    <textarea rows="4" placeholder="Tell us about your trip..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]"></textarea>
                    <button class="btn-primary w-full">Send Message</button>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
