🎅 Ho Ho Ho!

Santa Has Received {{ $letter->child_name }}'s Letter!

═══════════════════════════════════════════

Dear {{ $letter->parent_name }},

Thank you for choosing Letters2Santa to create a magical Christmas experience for {{ $letter->child_name }}! 🎄

Santa and his elves have received the letter and are already working on creating a special personalised Christmas storybook.

✓ PAYMENT SUCCESSFUL - Your order has been confirmed.

═══════════════════════════════════════════
📋 ORDER CONFIRMATION
═══════════════════════════════════════════

Order ID: {{ $letter->order_id }}
Child's Name: {{ $letter->child_name }}
Age Range: {{ $letter->age_range }} years old
Delivery Phone: {{ $letter->parent_mobile }}
Order Date: {{ $letter->created_at->format('F j, Y') }}
Amount Paid: ${{ number_format($letter->amount, 2) }} AUD

═══════════════════════════════════════════
🎁 WHAT HAPPENS NEXT?
═══════════════════════════════════════════

1. 📖 SANTA'S WORKSHOP CREATES MAGIC
Our elves are creating {{ $letter->child_name }}'s personalised
storybook with their name woven into a magical Christmas adventure!

2. 🎄 CHRISTMAS DAY DELIVERY
On Christmas Day morning, {{ $letter->child_name }} will receive a
special Email to {{ $letter->parent_mobile }} with their magical surprise!

3. ✨ MAGICAL CONTENTS
The Email will include a link to download:
• Personalised Christmas E-book (PDF)
• Printable Certificate from Santa
• Personal letter from Santa

═══════════════════════════════════════════
💬 NEED HELP?
═══════════════════════════════════════════

If you have any questions or need to update your details:

📧 Email: workshop@letters2santa.com
📋 Reference: {{ $letter->order_id }}

═══════════════════════════════════════════

❤️ Know other families who'd love this magical experience?
Share Letters2Santa: {{ config('app.url') }}

---

🎅 Letters2Santa
Spreading Christmas Magic 2025

🔒 Secure Payments | 🛡️ Privacy Protected | ❤️ Supporting Charity

This email was sent to {{ $letter->parent_email }}
© {{ date('Y') }} Letters2Santa. All rights reserved.
