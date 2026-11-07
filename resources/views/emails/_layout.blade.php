<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Letters2Santa')</title>
</head>
<body style="margin:0;padding:0;background:#f3efe6;font-family:Georgia,'Times New Roman',serif;color:#2b2b2b;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3efe6;padding:24px 0;">
<tr><td align="center">
<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#fffdf8;border-radius:16px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.08);">
  <tr>
    <td style="background:@yield('header_bg', '#C41E3A');padding:28px 32px;text-align:center;color:#fff;">
      <div style="font-size:14px;letter-spacing:2px;text-transform:uppercase;opacity:.85;">@yield('kicker', 'North Pole Post')</div>
      <h1 style="margin:8px 0 0;font-size:30px;line-height:1.2;font-weight:normal;">@yield('heading')</h1>
    </td>
  </tr>
  <tr>
    <td style="padding:32px;font-size:17px;line-height:1.65;">
      @yield('content')
    </td>
  </tr>
  <tr>
    <td style="padding:20px 32px 28px;background:#165B33;color:#dfeee4;font-size:12px;line-height:1.6;text-align:center;font-family:Arial,Helvetica,sans-serif;">
      Letters2Santa &middot; {{ config('letters.business.legal_name') }} &middot; ABN {{ config('letters.business.abn') }}<br>
      You received this because a letter was sent to Santa using this email address.
      <a href="{{ url('/privacy') }}" style="color:#fff;">Privacy</a> &middot; <a href="{{ url('/contact') }}" style="color:#fff;">Contact</a>
    </td>
  </tr>
</table>
</td></tr>
</table>
</body>
</html>
