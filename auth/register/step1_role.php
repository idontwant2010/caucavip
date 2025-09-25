<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký - Bước 1 | Câu Cá VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header text-center bg-primary text-white">
            <h4>Hello, bạn là Cần thủ? hay bạn là Chủ hồ câu?</h4>
          </div>
          <div class="card-body"  >
            <form action="step2_info.php" method="get">
              <div class="form-check "></br>
                <input class="form-check-input " type="radio" name="role" id="canthu" value="canthu" required>
                <label class="form-check-label " for="canthu">🎣 Đăng ký làm Cần Thủ - Lưu thành tích</label>
                <div class="form-text ms-4">-Chọn lựa hồ câu: cá chép, cá phi, cá trắm phù hợp sở thích</div>             
                <div class="form-text ms-4">-Check nhanh giá hồ, google map, các game, đánh giá. </div>			  
	            <div class="form-text ms-4">-Đánh giá hồ câu, gửi góp ý cho chủ hồ.</div>		  
	            <div class="form-text ms-4">-Tính và lưu thành tích bằng hệ thống EXP "6 cấp Đài Sư": theo số tỉnh, game</div>	
			  </div></br>
              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="role" id="chuho" value="chuho" required>
                <label class="form-check-label" for="chuho">🏠 Đăng ký làm Chủ Hồ - Quản lý Hồ Câu</label>
                <div class="form-text ms-4">-Miễn phí phần mềm quản lý (60 ngày): đặt vé, thanh toán, in bill... </div>
				<div class="form-text ms-4">-Miễn phí hệ thống cân tự động (60 ngày): tự cân cá tính tiền, tự cân xôi...</div>
				<div class="form-text ms-4">-Kết nối lượng cần thủ chất lượng, review hồ, đánh giá hồ...</div>			  	
				</div>
			 
			  
			  
              <button type="submit" class="btn btn-success w-100">Tiếp tục</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
