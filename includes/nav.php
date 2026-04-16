<?php
$search_keyword = $_GET['search'] ?? '';
?>

<nav class="navbar">
    <div class="logo">
        <a href="index.php">
            <h2>Phúc An Châu</h2>
        </a>
    </div>
    <div class="nav-links">
        <form method="GET" action="index.php" class="search-bar">
            <input type="text" id="search-input" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo $search_keyword; ?>">
            <button class="search-btn"></button>
        </form>
        <a href="cart.php">Giỏ hàng</a>
        <?php if ($role === 'admin'): ?>
            <a href="admin.php">Quản trị</a>
        <?php endif; ?>
        <span class="user-greeting"> Chào, <strong>
            <?php if ($role === 'admin' || $role === 'user') {
            ?>
                <div class="dropdown user-greeting">
                <button class="dropbtn"><?php echo $username; ?></button>
                    <div class="dropdown-content">
                    <a href="profile.php">Trang cá nhân</a>
                    <a href="javascript:void(0)" onclick="showDevelopmentMessage()">Cửa hàng</a>
                            <?php if ($role === 'user'): ?>
            <a href="message_page.php">Chat với Dược sĩ</a>
        <?php endif; ?>
        <?php if ($role === 'admin'): ?>
            <a href="admin_message.php">Chat với khách hàng</a>
        <?php endif; ?>
                
                    </div>
                </div>
    
            <?php
            } else {
                echo 'Khách';
            } ?>
        </strong></span>
                <?php if ($role === 'admin' || $role === 'user') {
            echo '<a href="auth/logout.php" class="logout-btn">Đăng xuất</a>';
        } else {
            echo '<a href="auth/login.php" class="logout-btn">Đăng nhập</a>';
        } ?>
    </div>
</nav>

<script>
    function showDevelopmentMessage() {
        alert("Chức năng đang được phát triển!");
    }
</script> 
<style>
.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    background: none;
    border: none;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
     font-family: 'Times New Roman', Times, serif, sans-serif;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 1000;
}

.dropdown-content a {
    color: black;
    padding: 10px 14px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}

</style>