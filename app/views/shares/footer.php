<footer class="main-footer">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase footer-heading">Quản lý thư viện</h5>
                <p class="footer-text">Hệ thống quản lý thư viện thông minh, mang đến trải nghiệm tốt nhất cho người dùng.</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase footer-heading">Liên kết nhanh</h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="/Book/" class="footer-link">Danh sách sách</a></li>
                    <li><a href="/Book/add" class="footer-link">Thêm sách</a></li>
                    <li><a href="/account/login" class="footer-link">Đăng nhập</a></li>
                    <li><a href="/account/register" class="footer-link">Đăng ký</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase footer-heading">Kết nối với chúng tôi</h5>
                <div class="social-icons">
                    <a href="#" class="social-icon-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-bar">
        © 2025 Quản lý thư viện. All rights reserved.
    </div>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .container {
        flex: 1; /* Allows the container (main content) to grow and push footer down */
    }
    .main-footer {
        background: linear-gradient(to right, #34495e, #2c3e50); /* Darker blue-gray gradient */
        color: #ecf0f1; /* Light text color */
        padding: 40px 0 0; /* Adjust padding top, copyright bar has its own padding */
        font-size: 0.95em;
        margin-top: auto; /* Pushes footer to the bottom */
    }
    .main-footer .footer-heading {
        color: #f0f2f5; /* Slightly lighter heading color */
        font-weight: bold;
        margin-bottom: 20px;
        position: relative;
    }
    .main-footer .footer-heading::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 50px;
        height: 2px;
        background-color: #3498db; /* Blue accent line */
    }
    .main-footer .footer-text {
        line-height: 1.6;
        color: #bdc3c7; /* Lighter grey for body text */
    }
    .main-footer .footer-link {
        color: #bdc3c7 !important; /* Link color */
        text-decoration: none;
        transition: color 0.3s ease;
        padding: 5px 0;
        display: block;
    }
    .main-footer .footer-link:hover {
        color: #3498db !important; /* Blue on hover */
    }
    .main-footer .social-icons {
        display: flex;
        margin-top: 10px;
    }
    .main-footer .social-icon-link {
        color: #ecf0f1 !important; /* Icon color */
        font-size: 1.5em;
        margin-right: 15px;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    .main-footer .social-icon-link:hover {
        color: #3498db !important; /* Blue on hover */
        transform: translateY(-3px);
    }
    .copyright-bar {
        background-color: #2c3e50; /* Slightly darker shade for copyright bar */
        color: #ecf0f1;
        padding: 15px 0;
        text-align: center;
        font-size: 0.9em;
        border-top: 1px solid rgba(255,255,255,0.1); /* Subtle border top */
    }
</style>

</body>
</html>