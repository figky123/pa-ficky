<section class="bg-light p-3 p-md-4 p-xl-5">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
   .login-container {
      max-width: 900px;
      margin: auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
      background: white;
    }
   .login-image {
      display: none;
    }
   .login-form {
      padding: 2rem;
    }
   .login-form h4 {
      margin-bottom: 1.5rem;
      font-size: 1.75rem;
      font-weight: bold;
      color: #333;
    }
   .login-footer {
      margin-top: 1.5rem;
      text-align: center;
    }
   .login-footer a {
      color: #007bff;
      text-decoration: none;
    }
   .login-footer a:hover {
      text-decoration: underline;
    }
    @media (min-width: 768px) {
     .login-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
      }
     .login-form {
        padding: 3rem;
      }
    }
    /* Add some new styles to make it more visually appealing */
   .login-container {
      background: linear-gradient(to bottom, #f7f7f7, #fff);
    }
   .login-form {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
   .login-form h4 {
      color: #337ab7;
    }
   .login-footer {
      background: #f7f7f7;
      padding: 1rem;
      border-top: 1px solid #ddd;
    }
   .login-footer a {
      color: #337ab7;
    }
    /* Make it more responsive on mobile devices */
    @media (max-width: 767px) {
     .login-container {
        margin: 20px auto;
        padding: 20px;
      }
     .login-image {
        display: none;
      }
     .login-form {
        padding: 20px;
      }
     .login-footer {
        padding: 10px;
      }
    }
  </style>
  <div class="container">
    <div class="login-container row g-0">
      <div class="col-md-6 d-none d-md-block">
        <img src="{{ asset('/assets/img/admin.png') }}" alt="Login User" class="login-image">
      </div>
      <div class="col-md-6">
        <div class="login-form">
          <h4 class="text-center">Login User</h4>
          <form class="user" method="post" action="/login" enctype="multipart/form-data">
            @csrf
            <div class="form-floating mb-3">
              <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
              <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
              <label class="form-check-label text-secondary" for="remember_me">
                Keep me logged in
              </label>
            </div>
            <div class="d-grid mb-3">
              <button class="btn btn-dark btn-lg" type="submit">Login Now</button>
            </div>
          </form>
          <div class="login-footer">
            <a href="register" class="d-block">Create new account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>