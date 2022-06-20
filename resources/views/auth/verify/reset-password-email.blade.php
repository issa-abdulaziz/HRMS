
<!-- BEGIN: Head-->
@extends('Auth.layout.master')

@section('content')

<!-- BEGIN: Body-->


<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; --bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity));">
    <div style="display: none;">A request to reset password was received from your PixInvent Account</div>
    <div role="article" aria-roledescription="email" aria-label="Reset your Password" lang="en">
      <table style="font-family: Montserrat, -apple-system, &#39;Segoe UI&#39;, sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tbody><tr>
          <td align="center" style="--bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity)); font-family: Montserrat, -apple-system, &#39;Segoe UI&#39;, sans-serif;" bgcolor="rgba(236, 239, 241, var(--bg-opacity))">
            <table class="sm-w-full" style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; width: 600px;" width="600" cellpadding="0" cellspacing="0" role="presentation">
              <tbody><tr>
                <td class="sm-py-32 sm-px-24" style="font-family: Montserrat, -apple-system, &#39;Segoe UI&#39;, sans-serif; padding: 48px; text-align: center;" align="center">
                  <a href="{{ route('login') }}">
                    <img src="{{ asset('images/Logo.png') }}" width="155" alt="" style="border: 0; max-width: 100%; line-height: 100%; vertical-align: middle;">
                  </a>
                  <h1>Human Resources Managment System</h1>
                </td>
              </tr>
              <tr>
                <td align="center" class="sm-px-24" style="font-family: &#39;Montserrat&#39;,Arial,sans-serif;">
                  <table style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tbody><tr>
                      <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, &#39;Segoe UI&#39;, sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                        <p style="font-weight: 600; font-size: 18px; margin-bottom: 0;">Hey</p>
                        <p style="font-weight: 700; font-size: 20px; margin-top: 0; --text-opacity: 1; color: #ff5850; color: rgba(255, 88, 80, var(--text-opacity));">{{ $mailData['name'] }}!</p>
                        <p style="margin: 0 0 24px;">
                          A request to reset password was received from your Account -
                          <a href="mailto:{{ env('MAIL_USERNAME') }}" class="hover-underline" style="--text-opacity: 1; color: #7367f0; color: rgba(115, 103, 240, var(--text-opacity)); text-decoration: none;">{{ $mailData['email'] }}</a>
                          (ID: {{ $mailData['id']  }}) .
                        </p>
                        <p style="margin: 0 0 24px;">Use this link to reset your password and login.</p>
                        {{-- <a href="https://pixinvent.com/?reset_password_url" style="display: block; font-size: 14px; line-height: 100%; margin-bottom: 24px; --text-opacity: 1; color: #7367f0; color: rgba(115, 103, 240, var(--text-opacity)); text-decoration: none;">https://pixinvent.com?reset_password_url</a> --}}
                        <table style="font-family: &#39;Montserrat&#39;,Arial,sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
                          <tbody><tr>
                            <td style="mso-padding-alt: 16px 24px; --bg-opacity: 1; background-color: #7367f0; background-color: rgba(115, 103, 240, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, &#39;Segoe UI&#39;, sans-serif;" bgcolor="rgba(115, 103, 240, var(--bg-opacity))">
                              <a href="{{ route('auth.reset-password',$mailData['token']) }}" style="display: block; font-weight: 600; font-size: 14px; line-height: 100%; padding: 16px 24px; --text-opacity: 1; color: #ffffff; color: rgba(255, 255, 255, var(--text-opacity)); text-decoration: none;">Reset Password →</a>
                            </td>
                          </tr>
                        </tbody></table>
                        <p style="margin: 24px 0;">
                          <span style="font-weight: 600;">Note:</span> This link is valid to change your password only once.
                        </p>
                        <p style="margin: 0;">
                          If you did not intend to deactivate your account or need our help keeping the account, please
                          contact us at
                          <a href="mailto:{{ env('MAIL_USERNAME') }}" class="hover-underline" style="--text-opacity: 1; color: #7367f0; color: rgba(115, 103, 240, var(--text-opacity)); text-decoration: none;">developer.test.202ok@gmail.com</a>
                        </p>
                        <table style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                          <tbody><tr>
                            <td style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; padding-top: 32px; padding-bottom: 32px;">
                              <div style="--bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity)); height: 1px; line-height: 1px;">‌</div>
                            </td>
                          </tr>
                        </tbody></table>
                        <p style="margin: 0 0 16px;">
                          Not sure why you received this email? Please
                          <a href="mailto:{{ env('MAIL_USERNAME') }}" class="hover-underline" style="--text-opacity: 1; color: #7367f0; color: rgba(115, 103, 240, var(--text-opacity)); text-decoration: none;">let us know</a>.
                        </p>
                        <p style="margin: 0 0 16px;">Thanks, <br>The HRMS Team</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; height: 20px;" height="20"></td>
                    </tr>

                    <tr>
                      <td style="font-family: &#39;Montserrat&#39;,Arial,sans-serif; height: 16px;" height="16"></td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
    </div>


</body>
<!-- END: Body-->
@endsection
