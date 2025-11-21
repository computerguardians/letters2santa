<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter Confirmation - Letters2Santa</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background: #f5f5f7;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '❄️';
            position: absolute;
            font-size: 100px;
            opacity: 0.1;
            top: -20px;
            right: -20px;
        }

        .header::after {
            content: '🎄';
            position: absolute;
            font-size: 80px;
            opacity: 0.1;
            bottom: -10px;
            left: -10px;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 10px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            margin: 0;
        }

        /* Content Section */
        .content {
            padding: 40px 30px;
        }

        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .greeting {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .greeting strong {
            color: #C41E3A;
        }

        /* Order Card */
        .order-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid #e5e7eb;
        }

        .order-card h2 {
            color: #C41E3A;
            font-size: 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-details {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .order-details tr {
            background: white;
        }

        .order-details td {
            padding: 12px 16px;
        }

        .order-details td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 45%;
            border-radius: 8px 0 0 8px;
        }

        .order-details td:last-child {
            color: #1a1a1a;
            border-radius: 0 8px 8px 0;
        }

        .order-id {
            font-family: 'Courier New', monospace;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        /* What's Next Section */
        .whats-next {
            margin: 32px 0;
        }

        .whats-next h2 {
            color: #165B33;
            font-size: 22px;
            text-align: center;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .timeline {
            position: relative;
        }

        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 25px;
            top: 50px;
            width: 2px;
            height: calc(100% - 30px);
            background: linear-gradient(180deg, #e5e7eb 0%, transparent 100%);
        }

        .timeline-icon {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
        }

        .timeline-content {
            flex: 1;
            padding-top: 4px;
        }

        .timeline-content h3 {
            color: #1a1a1a;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .timeline-content p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .timeline-content ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
            color: #6b7280;
            font-size: 14px;
        }

        .timeline-content li {
            margin: 4px 0;
        }

        /* Alert Box */
        .alert-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 24px 0;
            display: flex;
            gap: 12px;
            align-items: start;
        }

        .alert-box-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .alert-box-content {
            flex: 1;
        }

        .alert-box-content strong {
            color: #92400e;
            display: block;
            margin-bottom: 4px;
        }

        .alert-box-content p {
            color: #78350f;
            margin: 0;
            font-size: 14px;
        }

        /* Message Box */
        .message-box {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }

        .message-box h3 {
            color: #C41E3A;
            font-size: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-box p {
            color: #6b7280;
            font-style: italic;
            margin: 0;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        /* CTA Button */
        .cta-section {
            text-align: center;
            margin: 32px 0;
            padding: 32px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 16px rgba(196, 30, 58, 0.3);
            transition: transform 0.2s;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196, 30, 58, 0.4);
        }

        /* Support Box */
        .support-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }

        .support-box h3 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .support-box p {
            color: #1e3a8a;
            font-size: 14px;
            margin: 6px 0;
        }

        .support-box a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: #f9fafb;
            padding: 32px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-brand {
            font-size: 20px;
            font-weight: 700;
            color: #C41E3A;
            margin-bottom: 8px;
        }

        .footer-tagline {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .footer-badges {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin: 16px 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            font-size: 12px;
            padding: 6px 12px;
            background: white;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
        }

        .footer-legal {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 16px;
            line-height: 1.5;
        }

        /* Social Section */
        .social-section {
            text-align: center;
            padding: 24px 30px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .social-section p {
            color: #1b5e20;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: #165B33;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #165B33;
            transition: all 0.2s;
        }

        .social-link:hover {
            background: #165B33;
            color: white;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .order-details td {
                padding: 10px 12px;
                font-size: 14px;
            }

            .timeline-item {
                gap: 15px;
            }

            .timeline-icon {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }

            .social-links {
                flex-direction: column;
            }

            .social-link {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo">🎅</div>
            <h1>Ho Ho Ho!</h1>
            <p>Santa Has Received {{ $letter->child_name }}'s Letter!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Badge -->
            <div style="text-align: center;">
                <span class="success-badge">
                    <span>✓</span>
                    <span>Payment Successful</span>
                </span>
            </div>

            <!-- Greeting -->
            <div class="greeting">
                <p>Dear <strong>{{ $letter->parent_name }}</strong>,</p>
                <p>Thank you for choosing Letters2Santa to create a magical Christmas experience for <strong>{{ $letter->child_name }}</strong>! 🎄</p>
                <p>Santa and his elves have received the letter and are already working on creating a special personalised Christmas storybook.</p>
            </div>

            <!-- Order Card -->
            <div class="order-card">
                <h2><span>📋</span> Order Confirmation</h2>
                <table class="order-details">
                    <tr>
                        <td>Order ID</td>
                        <td><span class="order-id">{{ $letter->order_id }}</span></td>
                    </tr>
                    <tr>
                        <td>Child's Name</td>
                        <td>{{ $letter->child_name }}</td>
                    </tr>
                    <tr>
                        <td>Age Range</td>
                        <td>{{ $letter->age_range }} years old</td>
                    </tr>
                    <tr>
                        <td>Delivery Phone</td>
                        <td>{{ $letter->parent_mobile }}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td>{{ $letter->created_at->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td>Amount Paid</td>
                        <td><strong>${{ number_format($letter->amount, 2) }} AUD</strong></td>
                    </tr>
                </table>
            </div>

            <!-- What's Next -->
            <div class="whats-next">
                <h2><span>🎁</span> What Happens Next?</h2>

                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon">📖</div>
                        <div class="timeline-content">
                            <h3>Santa's Workshop Creates Magic</h3>
                            <p>Our elves are creating {{ $letter->child_name }}'s personalised storybook with their name woven into a magical Christmas adventure!</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon">🎄</div>
                        <div class="timeline-content">
                            <h3>Christmas Day Delivery</h3>
                            <p>On <strong>Christmas Day morning</strong>, {{ $letter->child_name }} will receive a special Email to <strong>{{ $letter->parent_mobile }}</strong> with their magical surprise!</p>
                        </div>
                    </div>


                </div>
            </div>


            <!-- Message to Santa -->
            @if ($letter->message_to_santa)
                <div class="message-box">
                    <h3><span>📝</span> {{ $letter->child_name }}'s Message to Santa</h3>
                    <p>{{ $letter->message_to_santa }}</p>
                </div>
            @endif

            <!-- CTA -->
            <div class="cta-section">
                <p style="color: #6b7280; margin-bottom: 16px;">Want to keep track of your order?</p>
                <a href="{{ config('app.url') }}" class="cta-button">Visit Letters2Santa</a>
            </div>

            <!-- Support Box -->
            <div class="support-box">
                <h3><span>💬</span> Need Help?</h3>
                <p>If you have any questions or need to update your details:</p>
                <p>📧 Email: <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></p>
                <p>📋 Reference: <strong>{{ $letter->order_id }}</strong></p>
            </div>
        </div>

        <!-- Social Section -->
        <div class="social-section">
            <p><strong>❤️ Know other families who'd love this magical experience?</strong></p>
            <div class="social-links">
                <a href="#" class="social-link">
                    <span>📘</span>
                    <span>Share on Facebook</span>
                </a>
                <a href="#" class="social-link">
                    <span>💬</span>
                    <span>Share on WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-brand">🎅 Letters2Santa</div>
            <div class="footer-tagline">Spreading Christmas Magic Since 2024</div>

            <div class="footer-badges">
                <span class="badge">🔒 Secure Payments</span>
                <span class="badge">🛡️ Privacy Protected</span>
                <span class="badge">❤️ Supporting Charity</span>
            </div>

            <div class="footer-legal">
                This email was sent to {{ $letter->parent_email }}<br>
                © {{ date('Y') }} Letters2Santa. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>
