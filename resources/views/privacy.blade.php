@extends('layout')

@section('title', 'Privacy Policy - Letters2Santa')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="santa-font mb-4" style="color: var(--christmas-red);">Privacy Policy</h1>
                    <p class="text-muted mb-4"><strong>Last Updated:</strong> November {{ date('Y') }}</p>

                    <div class="alert alert-success">
                        <i class="fas fa-shield-alt"></i> <strong>Your Privacy Matters</strong><br>
                        We take your privacy seriously. This policy explains how we collect, use, and protect your personal information.
                    </div>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">1. Who We Are</h2>
                    <p><strong>Letters2Santa</strong> is operated by <strong>Traxrift Pty Ltd</strong> (ABN: 49 648 079 841), registered in Australia.</p>
                    <p><strong>Contact Us:</strong></p>
                    <ul>
                        <li>Email: <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></li>
                        <li>Website: <a href="https://letters2santa.com">letters2santa.com</a></li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">2. Information We Collect</h2>

                    <h3 class="h5 mt-3 mb-2">2.1 Personal Information You Provide</h3>
                    <p>When you use our service, you provide us with:</p>
                    <ul>
                        <li><strong>Child's Information:</strong>
                            <ul>
                                <li>First name</li>
                                <li>Age range (3-5, 6-8, or 9-12 years)</li>
                                <li>Message to Santa</li>
                                <li>Optional: Photo (for cartoon character creation)</li>
                            </ul>
                        </li>
                        <li><strong>Parent/Guardian Information:</strong>
                            <ul>
                                <li>Full name</li>
                                <li>Email address</li>
                                <li>Mobile phone number</li>
                            </ul>
                        </li>
                        <li><strong>Payment Information:</strong>
                            <ul>
                                <li>Processed securely by Stripe (we never see your full card details)</li>
                                <li>We only receive payment confirmation and transaction ID</li>
                            </ul>
                        </li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">2.2 Information Automatically Collected</h3>
                    <ul>
                        <li><strong>Technical Data:</strong> IP address, browser type, device information</li>
                        <li><strong>Usage Data:</strong> Pages visited, time spent, interactions</li>
                        <li><strong>Cookies:</strong> Essential cookies for site functionality (see Cookie Policy below)</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">3. How We Use Your Information</h2>
                    <p>We use your information to:</p>

                    <h3 class="h5 mt-3 mb-2">3.1 Provide Our Service</h3>
                    <ul>
                        <li>Create personalised e-books with your child's name</li>
                        <li>Generate cartoon characters from uploaded photos (if provided)</li>

                        <li>Deliver digital content on Christmas Day</li>
                        <li>Process payments securely</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">3.2 Communication</h3>
                    <ul>
                        <li>Send order confirmations</li>
                        <li>Provide customer support</li>
                        <li>Send delivery notifications</li>
                        <li>Respond to your inquiries</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">3.3 Legal & Business Operations</h3>
                    <ul>
                        <li>Comply with legal obligations</li>
                        <li>Prevent fraud and abuse</li>
                        <li>Resolve disputes</li>
                        <li>Improve our service</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">4. How We Protect Your Information</h2>

                    <h3 class="h5 mt-3 mb-2">4.1 Security Measures</h3>
                    <ul>
                        <li><strong>Encryption:</strong> All data transmitted via SSL/TLS encryption</li>
                        <li><strong>Secure Storage:</strong> Data stored in encrypted AWS cloud servers</li>
                        <li><strong>Payment Security:</strong> PCI-DSS compliant payment processing via Stripe</li>
                        <li><strong>Access Controls:</strong> Strict internal access limitations</li>
                        <li><strong>Regular Audits:</strong> Security reviews and updates</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">4.2 Photo Security</h3>
                    <p>If you upload a child's photo:</p>
                    <ul>
                        <li>Stored in private, encrypted AWS S3 buckets</li>
                        <li>Accessible only via unique, time-limited URLs</li>
                        <li><strong>Automatically deleted</strong> within 7 days after e-book delivery</li>
                        <li>Never shared with third parties</li>
                        <li>Never used for marketing or AI training</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">5. Data Retention</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Data Type</th>
                                <th>Retention Period</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Order information</td>
                                <td>7 years</td>
                                <td>Tax and legal requirements</td>
                            </tr>
                            <tr>
                                <td>Child's photo</td>
                                <td>7 days after delivery</td>
                                <td>Then automatically deleted</td>
                            </tr>
                            <tr>
                                <td>Email records</td>
                                <td>1 year</td>
                                <td>Customer support and compliance</td>
                            </tr>
                            <tr>
                                <td>Payment records</td>
                                <td>7 years</td>
                                <td>Financial compliance</td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">6. Who We Share Your Information With</h2>

                    <h3 class="h5 mt-3 mb-2">6.1 Essential Service Providers</h3>
                    <ul>
                        <li><strong>Stripe:</strong> Payment processing (PCI-DSS compliant)</li>
                        <li><strong>AWS (Amazon Web Services):</strong> Cloud hosting and storage</li>

                        <li><strong>Email Service:</strong> Transactional email delivery</li>
                    </ul>
                    <p><em>All service providers are bound by strict confidentiality and data protection agreements.</em></p>

                    <h3 class="h5 mt-3 mb-2">6.2 We NEVER Share Data With:</h3>
                    <ul>
                        <li>❌ Marketing companies</li>
                        <li>❌ Data brokers</li>
                        <li>❌ Advertisers</li>
                        <li>❌ Social media platforms</li>
                        <li>❌ Any third party for their marketing purposes</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">6.3 Legal Requirements</h3>
                    <p>We may disclose information if required by law or to:</p>
                    <ul>
                        <li>Comply with legal process (court orders, subpoenas)</li>
                        <li>Protect our legal rights</li>
                        <li>Prevent fraud or illegal activity</li>
                        <li>Protect child safety</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">7. Your Rights</h2>
                    <p>You have the right to:</p>

                    <h3 class="h5 mt-3 mb-2">7.1 Access</h3>
                    <ul>
                        <li>Request a copy of your personal data</li>
                        <li>Ask what information we hold about you</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">7.2 Correction</h3>
                    <ul>
                        <li>Update incorrect or incomplete information</li>
                        <li>Request corrections to your data</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">7.3 Deletion</h3>
                    <ul>
                        <li>Request deletion of your personal data</li>
                        <li>Note: We must retain some data for legal/tax purposes (7 years)</li>
                        <li>Photos are automatically deleted after 7 days</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">7.4 Objection</h3>
                    <ul>
                        <li>Object to processing of your data for certain purposes</li>
                        <li>Withdraw consent at any time</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">7.5 Portability</h3>
                    <ul>
                        <li>Request your data in a portable format</li>
                        <li>Transfer your data to another service</li>
                    </ul>

                    <p><strong>To exercise your rights:</strong> Email <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your request and order ID. We'll respond within 30 days.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">8. Children's Privacy</h2>
                    <p><strong>We take children's privacy very seriously.</strong></p>
                    <ul>
                        <li>Our service is intended for use by parents/guardians, not children</li>
                        <li>We only collect minimal child information (first name, age range, message)</li>
                        <li>We comply with global children's privacy laws</li>
                        <li>Photos are optional and deleted within 7 days</li>
                        <li>We never contact children directly</li>
                        <li>We never use child data for marketing</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">9. International Data Transfers</h2>
                    <p>Your data may be stored and processed in:</p>
                    <ul>
                        <li><strong>Australia:</strong> Primary data center location</li>
                        <li><strong>AWS Regions:</strong> Secure cloud infrastructure (Asia-Pacific region)</li>
                        <li>All transfers comply with international data protection laws</li>
                        <li>We use standard contractual clauses for data protection</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">10. Cookies & Tracking</h2>

                    <h3 class="h5 mt-3 mb-2">10.1 Essential Cookies</h3>
                    <p>We use cookies for:</p>
                    <ul>
                        <li>Session management (keeping you logged in)</li>
                        <li>Security (preventing fraud)</li>
                        <li>Payment processing</li>
                    </ul>

                    <h3 class="h5 mt-3 mb-2">10.2 We DO NOT Use</h3>
                    <ul>
                        <li>❌ Advertising cookies</li>
                        <li>❌ Social media tracking</li>
                        <li>❌ Third-party analytics (like Google Analytics)</li>
                        <li>❌ Behavioral tracking</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">11. Third-Party Links</h2>
                    <p>Our website may contain links to:</p>
                    <ul>
                        <li>Stripe payment pages</li>
                        <li>Social media (if you choose to share)</li>
                        <li>Charity partners</li>
                    </ul>
                    <p>These sites have their own privacy policies. We're not responsible for their practices.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">12. Marketing Communications</h2>
                    <p><strong>We do NOT send marketing emails.</strong></p>
                    <p>You will only receive:</p>
                    <ul>
                        <li>Order confirmation (transactional)</li>
                        <li>Delivery notification (transactional)</li>
                        <li>Customer support responses</li>
                    </ul>
                    <p>No newsletters, no promotional emails, no spam. Ever.</p>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">13. Data Breach Notification</h2>
                    <p>In the unlikely event of a data breach:</p>
                    <ul>
                        <li>We'll notify affected users within 72 hours</li>
                        <li>We'll inform relevant authorities as required by law</li>
                        <li>We'll take immediate action to secure systems</li>
                        <li>We'll provide guidance on protective measures</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">14. Changes to This Policy</h2>
                    <p>We may update this privacy policy to reflect:</p>
                    <ul>
                        <li>Changes in laws or regulations</li>
                        <li>New features or services</li>
                        <li>Improved privacy practices</li>
                    </ul>
                    <p>We'll notify you of significant changes via:</p>
                    <ul>
                        <li>Email notification</li>
                        <li>Website announcement</li>
                        <li>Updated "Last Updated" date</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">15. Legal Basis for Processing (GDPR)</h2>
                    <p>For users in the EU/UK, we process data based on:</p>
                    <ul>
                        <li><strong>Contract:</strong> To fulfill our service to you</li>
                        <li><strong>Consent:</strong> For optional photo uploads</li>
                        <li><strong>Legal Obligation:</strong> For tax and compliance records</li>
                        <li><strong>Legitimate Interest:</strong> For fraud prevention and service improvement</li>
                    </ul>

                    <h2 class="h4 mt-4 mb-3" style="color: var(--christmas-green);">16. Complaints</h2>
                    <p>If you're unhappy with how we handle your data:</p>
                    <ol>
                        <li>Contact us first: <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></li>
                        <li>We'll investigate and respond within 30 days</li>
                        <li>If unresolved, you can contact your local data protection authority</li>
                    </ol>

                    <div class="alert alert-info mt-5">
                        <i class="fas fa-shield-alt"></i> <strong>Questions About Privacy?</strong><br>
                        We're here to help! Email us at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with any privacy concerns or questions.
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
