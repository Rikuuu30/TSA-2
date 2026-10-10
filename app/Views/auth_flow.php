<section class="page-intro auth-intro">
    <div>
        <p class="eyebrow dark-eyebrow">AI-assisted learning activity</p>
        <h1>Login and <em>logout.</em></h1>
        <p>Trace the path from a submitted login form to a protected page, then review what a safe logout must do.</p>
    </div>
</section>

<section class="content-section inner-content auth-content">
    <div class="auth-notice" role="note">
        <strong>Learning activity and local demo</strong>
        <p>The app now has a simple demo sign-in, session-based route protection, and sign-out so you can observe this flow. It is a classroom demonstration, not a production-ready account service.</p>
    </div>

    <section class="auth-section" aria-labelledby="draft-title">
        <div class="auth-section-heading">
            <div><p class="eyebrow dark-eyebrow">Starting point</p><h2 id="draft-title">Initial flow draft</h2></div>
            <span class="auth-label">Example draft to review</span>
        </div>
        <p class="auth-copy">The activity prompt left the draft blank. This sample gives you a starting point; review it and put it into your own words before treating it as your original work.</p>
        <ol class="auth-steps auth-steps-draft">
            <li><span>01</span><div><strong>Submit credentials</strong><p>The visitor enters a username and password in the login form and submits it to the application.</p></div></li>
            <li><span>02</span><div><strong>Check the account</strong><p>The application looks up the account and checks whether the submitted credentials are correct.</p></div></li>
            <li><span>03</span><div><strong>Start a session</strong><p>If the credentials are accepted, the application stores the user's ID and a logged-in value in the session.</p></div></li>
            <li><span>04</span><div><strong>Protect the page</strong><p>Before showing a protected page, the application checks that the session says the user is logged in.</p></div></li>
            <li><span>05</span><div><strong>Log out</strong><p>The logout action removes the logged-in value from the session.</p></div></li>
            <li><span>06</span><div><strong>Redirect the visitor</strong><p>A successful login goes to the dashboard; an unsuccessful login returns to the form; logout goes to the public home page.</p></div></li>
        </ol>
    </section>

    <section class="auth-section" aria-labelledby="review-title">
        <div class="auth-section-heading">
            <div><p class="eyebrow dark-eyebrow">AI review</p><h2 id="review-title">Coverage check</h2></div>
            <span class="auth-label">Six required parts</span>
        </div>
        <div class="auth-review-grid">
            <article><span>01 / Form submission</span><strong>Covered, but specify POST</strong><p>Send credentials in a POST request, include CSRF protection, and validate required fields on the server.</p></article>
            <article><span>02 / Password verification</span><strong>Needs safer detail</strong><p>Compare the submitted password with a securely stored hash; never store or compare plaintext passwords.</p></article>
            <article><span>03 / Session creation</span><strong>Needs regeneration</strong><p>Create the authenticated session only after verification and regenerate its ID to reduce session-fixation risk.</p></article>
            <article><span>04 / Protected access</span><strong>Make it server-side</strong><p>Check authorization on every protected request with a route filter or equivalent server-side guard.</p></article>
            <article><span>05 / Logout</span><strong>Clear the whole session</strong><p>Destroy/invalidate the session and expire its cookie; do not only remove a visible flag.</p></article>
            <article><span>06 / Redirects</span><strong>Validate destinations</strong><p>Use a safe internal destination after login and a predictable public destination after logout. Reject arbitrary external return URLs.</p></article>
        </div>
    </section>

    <section class="auth-section revised-section" aria-labelledby="revised-title">
        <div class="auth-section-heading">
            <div><p class="eyebrow dark-eyebrow">After review</p><h2 id="revised-title">Revised login/logout flow</h2></div>
            <span class="auth-label">Server-checked sequence</span>
        </div>
        <ol class="auth-steps auth-steps-revised">
            <li><span>01</span><div><strong>Show the login form</strong><p>A GET request displays the form. The form submits by POST with a CSRF token.</p></div></li>
            <li><span>02</span><div><strong>Validate and verify credentials</strong><p>The server validates required input, finds the account, and verifies the password against its stored secure hash. Failure returns a generic error without revealing whether the username exists.</p></div></li>
            <li><span>03</span><div><strong>Create a fresh authenticated session</strong><p>Only after success, regenerate the session ID and store the minimum necessary account identifier and authentication state. Never put a password in the session.</p></div></li>
            <li><span>04</span><div><strong>Guard protected routes</strong><p>A server-side authentication filter checks the session on every protected request. If the session is missing or expired, redirect to login and keep only a validated internal return path.</p></div></li>
            <li><span>05</span><div><strong>End the session on logout</strong><p>Use a CSRF-protected POST logout action. Invalidate the session data and cookie, then redirect to the public landing or login page.</p></div></li>
            <li><span>06</span><div><strong>Use safe redirects</strong><p>After login, go to a fixed dashboard or a validated local destination. After logout, go to a public page; never redirect to an unchecked external URL.</p></div></li>
        </ol>
    </section>

    <section class="auth-section audit-section" aria-labelledby="audit-title">
        <div class="auth-section-heading">
            <div><p class="eyebrow dark-eyebrow">Before implementation</p><h2 id="audit-title">Short security audit</h2></div>
            <span class="auth-label">Risks to avoid</span>
        </div>
        <div class="audit-list">
            <article><span>Risk 01</span><div><strong>Plaintext or weak password storage</strong><p>A database leak would expose reusable passwords. Store password hashes created with a strong password-hashing API and verify submitted passwords against the hash.</p></div></article>
            <article><span>Risk 02</span><div><strong>Session fixation or stolen session cookies</strong><p>An attacker may reuse or predict a session identifier. Regenerate the ID after login; use HTTPS and secure, HttpOnly, SameSite cookie settings in production; invalidate the session on logout.</p></div></article>
            <article><span>Risk 03</span><div><strong>Protected routes checked only in the interface</strong><p>Hiding a link does not prevent a direct URL request. Enforce authentication and any role permissions on the server for every protected route.</p></div></article>
            <article><span>Risk 04</span><div><strong>CSRF, repeated guessing, or unsafe redirects</strong><p>Protect state-changing requests with CSRF tokens, limit repeated login attempts, use generic credential errors, and allow only trusted internal redirect targets.</p></div></article>
        </div>
    </section>
</section>
