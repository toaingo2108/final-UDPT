<!DOCTYPE html>
<html>

<head>
    <title>QA Reviewer</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
    <style>
        main {
            overflow-x: auto;
        }

        #categories-list li {
            max-width: 50%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            align-items: center;
        }

        .mr-2 {
            margin-right: 4px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="index.php">Trang chủ</a>
            <div class="d-flex align-items-center">
                <div id='user-info' class="d-flex align-items-center"></div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </div>
    </nav>

    <main class="container mb-4">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            include($page . '.php');
        } else {
            echo '<div id="menu"></div>';
        }
        // else {
        //     echo '<div>
        //     <ul class="navbar-nav mr-auto d-flex flex-column">';

        //     $links = [
        //         ['link' => 'add-question', 'name' => 'Trang 1: Đặt câu hỏi'],
        //         ['link' => 'list-questions', 'name' => 'Trang 2: Danh sách câu hỏi'],
        //         ['link' => 'last-answers', 'name' => 'Trang 4: Danh sách câu trả lời mới nhất'],
        //         ['link' => 'last-evaluates', 'name' => 'Trang 5: Danh sách các đánh giá mới nhất'],
        //         ['link' => 'change-role', 'name' => 'Trang 6: Đổi vai trò'],
        //     ];

        //     foreach ($links as $link) {
        //         echo '
        //         <li class="nav-item ' . (isset($_GET['page']) && $_GET['page'] == $link['link'] ? 'active' : '') . '">
        //             <a class="nav-link" href="index.php?page=' . $link['link'] . '">' . $link['name'] . '</a>
        //         </li>
        //     ';
        //     }

        //     echo '</ul>
        // </div>';
        // }
        ?>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLongTitle">Đăng nhập</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='login-form'>
                        <div class="form-group">
                            <label for="login-form-username">Username</label>
                            <input class="form-control" id="login-form-username" name='username' required>
                        </div>
                        <div class="form-group">
                            <label for="login-form-password">Password</label>
                            <input class="form-control" id="login-form-password" name='password' type='password' required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" id='login-form-btn-submit'>Tiếp tục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>