<?php
session_start();
include 'includes/db.php';

if (!$conn) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Lấy danh sách loại thuốc (categories)
$ds_loai = $conn->query("SELECT id, name FROM categories");

// Thêm thuốc mới
if (isset($_POST['them_thuoc'])) {
    $ten = $_POST['ten_thuoc'];
    $loai = (int)$_POST['loai'];
    $gia = (float)$_POST['gia'];
    $discount = isset($_POST['giam_gia']) ? (float)$_POST['giam_gia'] : 0.0;
    $so_luong = (int)$_POST['so_luong'];
    $mo_ta = $_POST['mo_ta'];
    $ingredient = $_POST['thanh_phan'];
    $uses = $_POST['cong_dung'];
    $usage = $_POST['cach_dung'];
    $side_effects = $_POST['tac_dung_phu'];
    $warning = $_POST['luu_y'];
    $storage = $_POST['bao_quan'];

    // Tính final_price dựa trên price và discount
    $final_price = $gia * (1 - $discount / 100);

    $hinh_anh = '';
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'assets/img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $tmp_name = $_FILES['hinh_anh']['tmp_name'];
        $filename = time() . '_' . basename($_FILES['hinh_anh']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $hinh_anh = $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products
        (name, category_id, price, discount, final_price, quantity, description, image, ingredient, uses, `usage`, side_effects, warning, storage, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if ($stmt) {
        $stmt->bind_param("sididdissssssss", $ten, $loai, $gia, $discount, $final_price, $so_luong, $mo_ta, $hinh_anh, $ingredient, $uses, $usage, $side_effects, $warning, $storage);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Lỗi prepare statement: " . $conn->error;
        exit;
    }

    header("Location: qldp.php");
    exit();
}

// Xóa thuốc
if (isset($_GET['xoa'])) {
    $id = intval($_GET['xoa']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: qldp.php");
    exit();
}

// Cập nhật thuốc
if (isset($_POST['cap_nhat'])) {
    $id = $_POST['id'];
    $ten = $_POST['ten_thuoc'];
    $loai = $_POST['loai'];
    $gia = $_POST['gia'];
    $discount = isset($_POST['giam_gia']) ? (float)$_POST['giam_gia'] : 0;
    $so_luong = $_POST['so_luong'];
    $mo_ta = $_POST['mo_ta'];
    $ingredient = $_POST['thanh_phan'];
    $uses = $_POST['cong_dung'];
    $usage = $_POST['cach_dung'];
    $side_effects = $_POST['tac_dung_phu'];
    $warning = $_POST['luu_y'];
    $storage = $_POST['bao_quan'];

    // Tính final_price dựa trên price và discount
    $final_price = $gia * (1 - $discount / 100);

    $hinh_anh = null;
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'assets/img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $tmp_name = $_FILES['hinh_anh']['tmp_name'];
        $filename = time() . '_' . basename($_FILES['hinh_anh']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $hinh_anh = $filename;
        }
    }

    if ($hinh_anh) {
        $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, discount=?, final_price=?, quantity=?, description=?, image=?, ingredient=?, uses=?, `usage`=?, side_effects=?, warning=?, storage=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("sididdisssssssi", $ten, $loai, $gia, $discount, $final_price, $so_luong, $mo_ta, $hinh_anh, $ingredient, $uses, $usage, $side_effects, $warning, $storage, $id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, discount=?, final_price=?, quantity=?, description=?, ingredient=?, uses=?, `usage`=?, side_effects=?, warning=?, storage=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("sididdissssssi", $ten, $loai, $gia, $discount, $final_price, $so_luong, $mo_ta, $ingredient, $uses, $usage, $side_effects, $warning, $storage, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: qldp.php");
    exit();
}

// Tìm kiếm thuốc
$where = "1=1";
if (!empty($_GET['tu_khoa'])) {
    $tu_khoa = $conn->real_escape_string($_GET['tu_khoa']);
    $where .= " AND products.name LIKE '%$tu_khoa%'";
}

// Lấy danh sách thuốc
$result = $conn->query("SELECT products.*, categories.name AS ten_loai FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE $where ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quản lý dược phẩm</title>
    <style>
        body {
            font-family: 'Times new roman', sans-serif;
            background-color: #FFFFFF;
            padding: 20px;
            color: #333333;
            margin: 0;
        }

        h2 {
            text-align: center;
            background-image: linear-gradient(to right, #007bff, #00bfff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .filter-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            background: #e0f0ff;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
        }

        .filter-form input[type="text"] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #007bff;
            width: 320px;
            outline: none;
            box-sizing: border-box;
            color: #333;
        }

        .filter-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 600;
        }

        .filter-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Bọc table trong một div có scroll ngang khi màn hình nhỏ */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        /* Table căn chỉnh và style */
        table {
            width: 100%;
            margin: 0 auto 40px;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #cce4ff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 123, 255, 0.1);
        }

        /* Header */
        th,
        td {
            padding: 10px 6px;
            text-align: center;
            border: 1px solid #d6eaff;
            vertical-align: middle;
            color: #333333;
            min-width: 120px;
            max-height: 50px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Chỉnh riêng ô ID nhỏ lại */
        th.id-col,
        td.id-col {
            min-width: 60px;
            padding: 1px 4px;
            max-width: 60px;
        }

        /* Header màu xanh */
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        /* Input, select và textarea rộng hơn để xem full nội dung */
        td input[type="text"],
        td input[type="number"],
        td select,
        td textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 6px 10px;
            border: 1px solid #007bff;
            border-radius: 6px;
            font-size: 14px;
            background-color: #f0f8ff;
            margin: 2px 0;
            color: #333;
            resize: vertical;
            text-align: center;
        }

        /* Giảm chiều cao textarea cho gọn hơn */
        td textarea {
            min-height: 40px;
            max-height: 100px;
        }

        /* Button cập nhật màu xanh lá */
        input[type=submit].btn-update {
            background-color: #28a745;
            color: white;
            padding: 8px 14px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            min-width: 90px;
            text-align: center;
            font-weight: 600;
        }

        /* Hover nút cập nhật */
        input[type=submit].btn-update:hover {
            background-color: #1e7e34;
        }

        /* Nút xóa */
        a.btn.xoa {
            background-color: #ff5733;
            padding: 8px 14px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            min-width: 90px;
            text-align: center;
            font-weight: 600;
            color: white;
            display: inline-block;
            text-decoration: none;
        }

        a.btn.xoa:hover {
            background-color: #cc4629;
        }

        /* Nút cập nhật và xóa thẳng hàng với nội dung */
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            height: 50px;
        }

        /* Ảnh thuốc nhỏ lại */
        img {
            border-radius: 6px;
            transition: transform 0.3s ease;
            max-width: 80px;
            max-height: 80px;
            display: block;
            margin: 0 auto 6px auto;
        }

        img:hover {
            transform: scale(1.1);
        }

        /* Responsive cho bảng trên màn hình nhỏ */
        @media screen and (max-width: 1024px) {

            th,
            td {
                min-width: 100px;
                padding: 10px 6px;
            }
        }

        @media screen and (max-width: 768px) {
            .table-wrapper {
                overflow-x: auto;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            tr {
                margin-bottom: 1rem;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
                white-space: normal;
                min-height: 50px;
            }

            td::before {
                position: absolute;
                left: 10px;
                top: 12px;
                white-space: nowrap;
                font-weight: bold;
                color: #007bff;
            }

            th {
                display: none;
            }
        }
    </style>

    <h2>Quản lý dược phẩm</h2>

    <form class="filter-form" method="GET" action="">
        <input type="text" name="tu_khoa" placeholder="🔍 Nhập tên thuốc cần tìm..." value="<?= htmlspecialchars($_GET['tu_khoa'] ?? '') ?>">
        <input type="submit" value="Tìm kiếm">
    </form>

    <!-- FORM THÊM -->
    <form method="POST" enctype="multipart/form-data" action="">
        <table>
            <tr>
                <td><input type="text" name="ten_thuoc" placeholder="Tên thuốc" required></td>
                <td>
                    <select name="loai" required>
                        <option value="">Chọn loại thuốc</option>
                        <?php while ($loai = $ds_loai->fetch_assoc()): ?>
                            <option value="<?= $loai['id'] ?>"><?= htmlspecialchars($loai['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td><input type="number" name="gia" min="0" step="0.01" placeholder="Giá gốc" required></td>
                <td><input type="number" name="giam_gia" min="0" max="100" step="0.01" placeholder="% Giảm giá"></td>
                <td><input type="number" name="so_luong" min="0" placeholder="Số lượng" required></td>
                <td><input type="file" name="hinh_anh" accept="image/*"></td>
                <td><textarea name="mo_ta" placeholder="Mô tả" rows="2"></textarea></td>
                <td><textarea name="thanh_phan" placeholder="Thành phần" rows="2"></textarea></td>
                <td><textarea name="cong_dung" placeholder="Công dụng" rows="2"></textarea></td>
                <td><textarea name="cach_dung" placeholder="Cách dùng" rows="2"></textarea></td>
                <td><textarea name="tac_dung_phu" placeholder="Tác dụng phụ" rows="2"></textarea></td>
                <td><textarea name="luu_y" placeholder="Lưu ý" rows="2"></textarea></td>
                <td><textarea name="bao_quan" placeholder="Bảo quản" rows="2"></textarea></td>
                <td><input type="submit" name="them_thuoc" value="Thêm thuốc" class="btn"></td>
            </tr>
        </table>
    </form>

    <!-- DANH SÁCH THUỐC -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thuốc</th>
                <th>Loại</th>
                <th>Giá gốc</th>
                <th>Giảm giá (%)</th>
                <th>Giá sau giảm</th>
                <th>Số lượng</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Thành phần</th>
                <th>Công dụng</th>
                <th>Cách dùng</th>
                <th>Tác dụng phụ</th>
                <th>Lưu ý</th>
                <th>Bảo quản</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST" enctype="multipart/form-data" action="">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <td><?= $row['id'] ?></td>
                        <td><input type="text" name="ten_thuoc" value="<?= htmlspecialchars($row['name']) ?>" required></td>
                        <td>
                            <select name="loai" required>
                                <?php
                                // Load lại danh sách loại cho select trong mỗi dòng
                                $ds_loai->data_seek(0);
                                while ($loai = $ds_loai->fetch_assoc()):
                                ?>
                                    <option value="<?= $loai['id'] ?>" <?= ($loai['id'] == $row['category_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($loai['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td><input type="number" name="gia" min="0" step="0.01" value="<?= $row['price'] ?>" required></td>
                        <td><input type="number" name="giam_gia" min="0" max="100" step="0.01" value="<?= $row['discount'] ?>"></td>
                        <td>
                            <?php
                            if ($row['discount'] > 0) {
                                echo '<span style="text-decoration: line-through; color:#888;">' . number_format($row['price'], 0, ',', '.') . ' đ</span><br>';
                                echo '<strong>' . number_format($row['final_price'], 0, ',', '.') . ' đ</strong>';
                            } else {
                                echo number_format($row['price'], 0, ',', '.') . ' đ';
                            }
                            ?>
                        </td>
                        <td><input type="number" name="so_luong" min="0" value="<?= $row['quantity'] ?>" required></td>
                        <td>
                            <?php if ($row['image'] && file_exists('assets/img/' . $row['image'])): ?>
                                <img src="assets/img/<?= htmlspecialchars($row['image']) ?>" alt="Ảnh thuốc">
                            <?php endif; ?>
                            <input type="file" name="hinh_anh" accept="image/*" style="margin-top:2px;">
                        </td>
                        <td><textarea name="mo_ta" rows="2"><?= htmlspecialchars($row['description']) ?></textarea></td>
                        <td><textarea name="thanh_phan" rows="2"><?= htmlspecialchars($row['ingredient']) ?></textarea></td>
                        <td><textarea name="cong_dung" rows="2"><?= htmlspecialchars($row['uses']) ?></textarea></td>
                        <td><textarea name="cach_dung" rows="2"><?= htmlspecialchars($row['usage']) ?></textarea></td>
                        <td><textarea name="tac_dung_phu" rows="2"><?= htmlspecialchars($row['side_effects']) ?></textarea></td>
                        <td><textarea name="luu_y" rows="2"><?= htmlspecialchars($row['warning']) ?></textarea></td>
                        <td><textarea name="bao_quan" rows="2"><?= htmlspecialchars($row['storage']) ?></textarea></td>
                        <td class="action-buttons">
                            <input type="submit" name="cap_nhat" value="Cập nhật" class="btn-update" onclick="return confirm('Bạn có muốn cập nhật?');">
                            <a href="?xoa=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa thuốc này?');" class="btn xoa">Xóa</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </body>

</html>