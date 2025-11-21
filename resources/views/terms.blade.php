@extends('layout')

@section('title', 'Terms & Conditions - Letters2Santa')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="santa-font mb-4" style="color: var(--christmas-red);">Terms & Conditions</h1>
                    <p class="text-muted mb-4"><strong>Last Updated:</strong> November {{ date('Y') }}</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">1. About Our Service</h2>
                    <p>Letters2Santa is operated by <strong>Traxrift Pty Ltd</strong> (ABN: 49 648 079 841), an Australian company. By using our service, you agree to these terms and conditions.</p>
                    <p>Letters2Santa provides a digital personalised Christmas storybook service where parents can purchase a magical experience for their children, including:</p>
                    <ul>
                        <li>Personalised digital storybook (PDF format)</li>
                        <li>Printable certificate from Santa</li>
                        <li>Personal letter from Santa</li>

                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">2. Product Description</h2>
                    <p><strong>What You Receive:</strong></p>
                    <ul>
                        <li>A digital e-book file (PDF) personalised with your child's name</li>
                        <li>Age-appropriate content based on selected age range (3-5, 6-8, or 9-12 years)</li>
                        <li>Optional: Cartoon version of your child in the story (if photo uploaded)</li>
                        <li>Digital certificate and letter from Santa</li>

                    </ul>
                    <p><strong>What You Do NOT Receive:</strong></p>
                    <ul>
                        <li>Physical printed books (unless separately purchased)</li>
                        <li>Physical toys or gifts</li>
                        <li>A reply from the actual North Pole</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">3. Pricing & Payment</h2>
                    <ul>
                        <li>The service is priced at <strong>$9.49 AUD</strong> per letter</li>
                        <li>All prices are in Australian Dollars (AUD)</li>
                        <li>Payment is processed securely through Stripe</li>
                        <li>All sales are final once payment is processed</li>
                        <li>We accept major credit and debit cards</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">4. Order Process</h2>
                    <ol>
                        <li>Parent fills out letter form with child's details</li>
                        <li>Payment is processed via Stripe</li>
                        <li>Confirmation email sent to parent's email address</li>
                        <li>E-book is prepared and personalised</li>

                    </ol>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">5. Delivery</h2>
                    <p><strong>Delivery Method:</strong> Email</p>
                    <p><strong>Delivery Date:</strong> December 25th (Christmas Day), between 12:00 AM - 10:00 AM local time based on your timezone</p>

                    <ul>
                        <li>Your spam/junk folder for the backup email</li>

                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">6. Photo Upload</h2>
                    <p>If you choose to upload a photo of your child:</p>
                    <ul>
                        <li>Photos are stored securely in encrypted cloud storage (AWS S3)</li>
                        <li>Photos are used solely to create a cartoon character for the story</li>
                        <li>Photos are <strong>automatically deleted</strong> within 7 days after e-book delivery</li>
                        <li>We never share, sell, or use photos for any other purpose</li>
                        <li>Maximum file size: 5MB</li>
                        <li>Accepted formats: JPG, PNG</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">7. Refund Policy</h2>
                    <p><strong>Eligibility for Refunds:</strong></p>
                    <ul>
                        <li><strong>Before Christmas Day:</strong> Full refund available if requested at least 48 hours before December 25th</li>
                        <li><strong>Technical Issues:</strong> Full refund if e-book is not delivered due to our technical failure</li>
                        <li><strong>Incorrect Information:</strong> No refund if delivery fails due to incorrect contact information provided by you</li>
                    </ul>
                    <p><strong>Non-Refundable Situations:</strong></p>
                    <ul>
                        <li>After e-book has been successfully delivered</li>
                        <li>If you provided an incorrect mobile number or email address</li>
                        <li>If you're dissatisfied with the content (satisfaction is subjective)</li>
                        <li>Change of mind after delivery</li>
                    </ul>
                    <p><strong>How to Request a Refund:</strong> Email <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your order ID within 48 hours of your request.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">8. Intellectual Property</h2>
                    <ul>
                        <li>All content, including stories, illustrations, and designs, are owned by Traxrift Pty Ltd</li>
                        <li>You receive a personal, non-transferable license to use the e-book for personal, non-commercial use only</li>
                        <li>You may print the e-book for personal use</li>
                        <li>You may NOT resell, redistribute, or commercially exploit the content</li>
                        <li>Copyright infringement will be prosecuted to the fullest extent of the law</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">9. Age Restrictions</h2>
                    <p>This service is intended for:</p>
                    <ul>
                        <li>Parents or legal guardians aged 18 years or older</li>
                        <li>Purchasing on behalf of children aged 3-12 years</li>
                        <li>You must have parental authority to provide child's information</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">10. Content Standards</h2>
                    <p>We reserve the right to refuse or modify any letter that contains:</p>
                    <ul>
                        <li>Offensive, inappropriate, or harmful content</li>
                        <li>Hate speech, discrimination, or illegal content</li>
                        <li>Requests for physical gifts or items</li>
                        <li>Personal information of third parties</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">11. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by law:</p>
                    <ul>
                        <li>Letters2Santa is provided "as is" without warranties of any kind</li>
                        <li>We are not liable for any indirect, incidental, or consequential damages</li>
                        <li>Our total liability shall not exceed the amount you paid for the service ($9.49 AUD)</li>

                        <li>We are not liable for disappointment or emotional distress</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">12. Third-Party Services</h2>
                    <p>We use the following third-party services:</p>
                    <ul>
                        <li><strong>Stripe:</strong> Payment processing (subject to Stripe's terms)</li>
                        <li><strong>AWS:</strong> Cloud storage for photos and e-books</li>

                        <li>Each service has its own terms and privacy policies</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">13. Charitable Contributions</h2>
                    <p>A portion of proceeds is donated to children's charities. However:</p>
                    <ul>
                        <li>Donations are made at our discretion</li>
                        <li>Your purchase is NOT tax-deductible as a charitable donation</li>
                        <li>We reserve the right to change charity partners</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">14. Changes to Service</h2>
                    <p>We reserve the right to:</p>
                    <ul>
                        <li>Modify, suspend, or discontinue the service at any time</li>
                        <li>Change pricing (does not affect existing orders)</li>
                        <li>Update these terms and conditions (changes take effect immediately)</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">15. Governing Law</h2>
                    <p>These terms are governed by the laws of Australia. Any disputes shall be resolved in the courts of Victoria, Australia.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">16. Contact Information</h2>
                    <p><strong>Traxrift Pty Ltd</strong><br>
                        ABN: 49 648 079 841<br>
                        Email: <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a><br>
                        Website: <a href="https://letters2santa.com">letters2santa.com</a></p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">17. Severability</h2>
                    <p>If any provision of these terms is found to be unenforceable, the remaining provisions shall continue in full force and effect.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">18. Entire Agreement</h2>
                    <p>These terms, together with our Privacy Policy, constitute the entire agreement between you and Letters2Santa.</p>

                    <div class="alert alert-info mt-5">
                        <i class="fas fa-info-circle"></i> <strong>Questions?</strong> If you have any questions about these terms, please contact us at <a
                            href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-christmas">
                            <i class="fas fa-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
