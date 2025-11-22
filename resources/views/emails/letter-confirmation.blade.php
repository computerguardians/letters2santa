<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Letters2Santa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #C41E3A, #8B0000);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-family: 'Mountains of Christmas', cursive, serif;
        }
        .success-badge {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .order-details {
            background: #fff8dc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin-top: 0;
            color: #C41E3A;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .badge-free {
            background: #10b981;
            color: white;
        }
        .badge-premium {
            background: #C41E3A;
            color: white;
        }
        .next-steps {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .step {
            margin: 15px 0;
            padding-left: 30px;
            position: relative;
        }
        .step::before {
            content: '✓';
            position: absolute;
            left: 0;
            background: #10b981;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: bold;
        }
        .cta-button {
            display: inline-block;
            background: #C41E3A;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
        .upgrade-box {
            background: linear-gradient(135deg, #C41E3A, #8B0000);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        .upgrade-box h3 {
            margin-top: 0;
            color: #FFD700;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 15px;
            }
            .header {
                padding: 20px;
                margin: -15px -15px 20px -15px;
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎅 Letters2Santa 🎄</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px;">Ho Ho Ho!</p>
        </div>

        @if($letter->tier === 'free')
            <div class="success-badge">✅ FREE Order Confirmed!</div>
        @else
            <div class="success-badge">✅ Payment Successful!</div>
        @endif

        <h2 style="color: #165B33;">Dear {{ $letter->parent_name }},</h2>

        @if($letter->tier === 'free')
            <p>Thank you for choosing Letters2Santa! We're delighted to create a FREE magical Christmas storybook for <strong>{{ $letter->child_name }}</strong>!</p>
        @else
            <p>Thank you for your payment! We're thrilled to create a magical Christmas storybook for <strong>{{ $letter->child_name }}</strong>!</p>
        @endif

        <!-- Order Details -->
        <div class="order-details">
            <h3>📋 Order Details</h3>

            <div class="detail-row">
                <span class="detail-label">Order ID:</span>
                <span class="detail-value"><code>{{ $letter->order_id }}</code></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Package:</span>
                <span class="detail-value">
                    @if($letter->tier === 'free')
                        <span class="badge badge-free">✨ FREE Basic</span>
                    @else
                        <span class="badge badge-premium">⭐ Premium</span>
                    @endif
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Child's Name:</span>
                <span class="detail-value">{{ $letter->child_name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Age Range:</span>
                <span class="detail-value">{{ $letter->age_range }} years old</span>
            </div>

            @if($letter->tier === 'premium' || empty($letter->tier))
            <div class="detail-row">
                <span class="detail-label">Amount Paid:</span>
                <span class="detail-value"><strong style="color: #C41E3A;">$4.99 AUD</strong></span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Delivery Date:</span>
                <span class="detail-value"><strong>Christmas Day (Dec 25th)</strong></span>
            </div>
        </div>

        <!-- What's Included -->
        <div class="next-steps">
            <h3 style="margin-top: 0; color: #165B33;">🎁 What {{ $letter->child_name }} Will Receive:</h3>

            <div class="step">
                <strong>Personalized E-Book</strong><br>
                <small>A magical Christmas story featuring {{ $letter->child_name }}'s name throughout!</small>
            </div>

            @if($letter->tier === 'premium' || empty($letter->tier))
            <div class="step">
                <strong>Cartoon Character</strong><br>
                <small>{{ $letter->child_name }}'s photo transformed into a cartoon appearing in the story!</small>
            </div>

            <div class="step">
                <strong>Premium Certificate</strong><br>
                <small>A beautiful printable certificate from Santa</small>
            </div>
            @endif

            <div class="step">
                <strong>Letter from Santa</strong><br>
                <small>A personal message from Santa himself!</small>
            </div>

            <div class="step">
                <strong>Email Delivery</strong><br>
                <small>All delivered to <strong>{{ $letter->parent_email }}</strong> on Christmas morning!</small>
            </div>
        </div>

        <!-- Upgrade Box (Only for FREE tier) -->
        @if($letter->tier === 'free')
        <div class="upgrade-box">
            <h3>⭐ Want to See {{ $letter->child_name }} in the Story?</h3>
            <p style="margin: 15px 0;">Upgrade to Premium for only <strong style="font-size: 24px;">$4.99</strong> and add a cartoon version of {{ $letter->child_name }} to the storybook!</p>
            <p style="margin: 15px 0; font-size: 14px; opacity: 0.9;">✅ Everything in FREE + Cartoon Photo + Premium Certificate + High-Res PDF</p>
            <a href="{{ route('letter.create', ['tier' => 'premium']) }}" class="cta-button" style="background: #FFD700; color: #000;">
                UPGRADE TO PREMIUM - $4.99
            </a>
            <p style="margin: 10px 0 0 0; font-size: 13px;">⏰ Black Friday price ends Dec 2nd!</p>
        </div>
        @endif

        <!-- Important Information -->
        <div style="background: #fff3cd; padding: 15px; border-radius: 10px; margin: 20px 0;">
            <strong>📅 Delivery Date:</strong> Christmas Day (December 25th)<br>
            <strong>📧 Delivery Email:</strong> {{ $letter->parent_email }}<br>
            <strong>🔒 Privacy:</strong>
            @if($letter->tier === 'premium' || empty($letter->tier))
                Photos are automatically deleted 7 days after delivery
            @else
                Your information is secure and private
            @endif
        </div>

        <!-- Social Share -->
        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Love Letters2Santa? Share the magic!</strong></p>
            <p>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('black-friday')) }}" style="margin: 0 10px; color: #C41E3A; text-decoration: none;">📘 Share on Facebook</a>
                <a href="https://wa.me/?text={{ urlencode('Check out Letters2Santa - FREE Christmas storybooks! ' . route('black-friday')) }}" style="margin: 0 10px; color: #165B33; text-decoration: none;">💬 Share on WhatsApp</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Need Help?</strong><br>
            Email us at: <a href="mailto:workshop@letters2santa.com" style="color: #C41E3A;">workshop@letters2santa.com</a></p>

            <p style="margin-top: 20px;">
                Follow us:<br>
                <a href="https://www.facebook.com/letters2santaofficial" style="color: #C41E3A; margin: 0 5px;">Facebook</a> |
                <a href="https://www.instagram.com/letters2santaofficial" style="color: #C41E3A; margin: 0 5px;">Instagram</a><br>
                @letters2santaofficial
            </p>

            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                © {{ date('Y') }} Letters2Santa. All rights reserved.<br>
                Making Christmas magical, one story at a time! 🎅🎄
            </p>
        </div>
    </div>
</body>
</html>
