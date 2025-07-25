<style>
    /* CSS cho phần hero section */
    .hero-section {
        background: linear-gradient(to bottom right, #a2d2ff, #62b6cb); /* Gradient màu xanh nhẹ */
        color: white;
        text-align: center;
        padding: 8rem 0;
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .hero-section p {
        font-size: 1.25rem;
        max-width: 700px;
        margin-bottom: 2rem;
    }
    .search-container {
        display: flex;
        justify-content: center;
        margin-bottom: 3rem;
    }
    .search-container input {
        width: 400px;
        padding: 10px 15px;
        border-radius: 50px 0 0 50px;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .search-container button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 0 50px 50px 0;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .search-container button:hover {
        background-color: #0056b3;
    }

    /* CSS cho phần stats */
    .stats-section {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 2rem 3rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .stat-card p {
        font-size: 1rem;
        font-weight: 300;
        margin-bottom: 0;
    }

    /* CSS cho các nút CTA */
    .cta-buttons {
        display: flex;
        gap: 1rem;
    }
    .btn-cta {
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }
    .btn-cta-primary {
        background-color: #007bff;
        color: white;
        border: 2px solid #007bff;
    }
    .btn-cta-secondary {
        background-color: transparent;
        color: white;
        border: 2px solid white;
    }
</style>

<div class="hero-section">
    <h1>LIBSMART</h1>
    <p>Hệ thống quản lý thư viện thông minh</p>
    <p>Hơn 50,000 đầu sách đang chờ bạn khám phá. Từ văn học cổ điển đến khoa học hiện đại, tất cả đều có tại LIBSMART.</p>

    <div class="search-container">
        <input type="text" placeholder="Tìm kiếm sách, tác giả, ISBN..." />
        <button>Tìm kiếm</button>
    </div>

    <div class="stats-section">
        <div class="stat-card">
            <h3>50,000+</h3>
            <p>Đầu sách</p>
        </div>
        <div class="stat-card">
            <h3>15,000+</h3>
            <p>Thành viên</p>
        </div>
        <div class="stat-card">
            <h3>24/7</h3>
            <p>Truy cập online</p>
        </div>
    </div>

    <div class="cta-buttons">
        <a href="/account/register" class="btn-cta btn-cta-primary">Đăng ký thành viên →</a>
        <a href="/Book/index" class="btn-cta btn-cta-secondary">Khám phá ngay</a>
    </div>
</div>