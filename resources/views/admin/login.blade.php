<!-- resources/views/admin/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — Kelp</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F5F4F0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #fff;
            border: 1px solid #E5E4E0;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
        }

        .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }


        h1 { font-size: 22px; font-weight: 500; color: #1a1a18; margin-bottom: 4px; }

        .subheading { font-size: 14px; color: #6b6b66; margin-bottom: 1.75rem; }

        .field { margin-bottom: 1.1rem; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #6b6b66;
            margin-bottom: 6px;
        }

        .field input {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            border: 1px solid #D0CFC9;
            border-radius: 8px;
            background: #fff;
            color: #1a1a18;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input:focus {
            border-color: #E8500A;
            box-shadow: 0 0 0 3px rgba(232, 80, 10, 0.12);
        }

        .pass-wrap { position: relative; }

        .pass-wrap input { padding-right: 40px; }

        .eye-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b6b66;
            font-size: 16px;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .forgot {
            display: block;
            text-align: right;
            font-size: 12px;
            color: #E8500A;
            margin-top: 6px;
            text-decoration: none;
        }

        .forgot:hover { text-decoration: underline; }

        .submit-btn {
            width: 100%;
            height: 40px;
            background: #E8500A;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: background 0.15s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover { background: #c74308; }
        .submit-btn:active { transform: scale(0.98); }
        .submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }

        .error-box {
            display: none;
            background: #FCEBEB;
            border: 1px solid #F7C1C1;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            color: #A32D2D;
            margin-top: 1rem;
            align-items: center;
            gap: 8px;
        }

        .error-box.show { display: flex; }

        hr {
            border: none;
            border-top: 1px solid #E5E4E0;
            margin: 1.75rem 0;
        }

        .footer-note {
            font-size: 12px;
            color: #a0a09a;
            text-align: center;
        }

        .footer-note span { color: #E8500A; }
    </style>
</head>
<body>

<div class="card">
    <div class="logo-row">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Kelp logo" style="height: 38px; width: auto;" />
    </div>

    <h1>Welcome back</h1>
    <p class="subheading">Sign in to access the admin dashboard</p>

    <form id="adminLoginForm">
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" placeholder="admin@kelp.app" autocomplete="email" required />
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="pass-wrap">
                <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required />
                <button type="button" class="eye-btn" id="eyeBtn" aria-label="Toggle password visibility">
                    👁
                </button>
            </div>
           
        </div>

        <button type="submit" class="submit-btn" id="loginBtn">
            <span id="btnLabel">Sign in</span>
            <span id="btnIcon">→</span>
        </button>
    </form>

    <div class="error-box" id="errorBox">
        <span>⚠</span>
        <span id="errorMsg">Invalid credentials. Please try again.</span>
    </div>

    <hr />
    <p class="footer-note">Protected area — <span>Kelp</span> internal use only</p>
</div>

<script>
    // Toggle password visibility
    const eyeBtn = document.getElementById('eyeBtn');
    const passInput = document.getElementById('password');
    let passVisible = false;
    eyeBtn.addEventListener('click', () => {
        passVisible = !passVisible;
        passInput.type = passVisible ? 'text' : 'password';
        eyeBtn.textContent = passVisible ? '🙈' : '👁';
    });

    // Form submission
    document.getElementById('adminLoginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const loginBtn = document.getElementById('loginBtn');
        const btnLabel = document.getElementById('btnLabel');
        const btnIcon = document.getElementById('btnIcon');
        const errorBox = document.getElementById('errorBox');
        const errorMsg = document.getElementById('errorMsg');

        errorBox.classList.remove('show');
        loginBtn.disabled = true;
        btnLabel.textContent = 'Signing in…';
        btnIcon.textContent = '⏳';

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await axios.post('/api/admin/login', data);
            localStorage.setItem('kelp_token', response.data.token);
            window.location.href = '/admin/dashboard';
        } catch (err) {
            errorMsg.textContent = err.response?.data?.message || 'Login failed. Please check your credentials.';
            errorBox.classList.add('show');
        } finally {
            loginBtn.disabled = false;
            btnLabel.textContent = 'Sign in';
            btnIcon.textContent = '→';
        }
    });
</script>

</body>
</html>
