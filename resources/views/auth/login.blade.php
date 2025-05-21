<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
</head>
<body >
  <section style="min-height: 100vh; background-color: rgb(132, 177, 222);">
    <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; overflow: hidden;">
          <div class="row g-0">
          <div class="left-side" style="flex: 1; background-color:white; display: flex; justify-content: center; align-items: center; border-radius: 1rem 0 0 1rem;">
        <img src="image/login.jpg" alt="login form" class="img-fluid" style="max-width: 80%; height: auto;" />
    </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

              <form method="POST" action="{{ route('login') }}">
                @csrf

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <span class="h2 fw-bold mb-0">Selamat Datang!</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Silahkan masuk ke akun anda</h5>


                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="email">Email</label>
                  <input type="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukan email anda" style="font-size: 14px;" />
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukan password anda" style="font-size: 14px; "/>
                  </div>

              <div class="pt-1 mb-4">
              <button type="submit" class="btn btn-md btn-block"
  style="background-color: rgb(120, 169, 219); color: white; border: none;">
  Login
</button>

            </div>
                  <!-- <a class="small text-muted" href="#!">Forgot password?</a> -->
                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!"
                      style="color: #393f81;">Register here</a></p>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
