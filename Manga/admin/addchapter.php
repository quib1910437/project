<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['add'])) {
        $thutu = $_POST['thutu'];
        $img = $_FILES["img"]["name"];
        $idtruyen = $_POST['idtruyen'];
        $idchapter = intval($_GET['idchapter']);

        // get the image extension
        $extension = substr($img, strlen($img) - 4, strlen($img));
        // allowed extensions
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        //rename the image file
        $imgnewname = md5($img . time()) . $extension;
        // Code for move image into directory

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . $imgnewname);
            $sql = "INSERT INTO  chapter(thutu, img) VALUES(:thutu, :imgnewname)";
            $sql = "update  chapter set idtruyen=:idtruyen where idchapter=:idchapter";
            $query = $dbh->prepare($sql);
            $query->bindParam(':thutu', $thutu, PDO::PARAM_STR);
            $query->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                echo "<script>alert('Chapter update successfully');</script>";
                echo "<script>window.location.href='manage-books.php'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
                echo "<script>window.location.href='manage-books.php'</script>";
            }
        }
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Manga Website | Thêm Chapter</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('includes/header.php'); ?>
        <!-- MENU SECTION END-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Thêm Chappter</h4>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Thông tin truyện
                            </div>
                            <div class="panel-body">
                                <?php
                                $bookid = intval($_GET['bookid']);
                                $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblcategory.id as bookid,tblbooks.bookImage from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId  where tblbooks.id=:bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {               ?>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ảnh bìa</label>
                                                <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" width="100">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tên Truyện</label>
                                                <div type="text" name="bookname"><?php echo htmlentities($result->BookName); ?></div>
                                            </div>
                                        </div>
                                        <form role="form" method="post" enctype="multipart/form-data">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Thứ tự chap<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="thutu" autocomplete="off" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ảnh Chapter<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="file" name="img[]" autocomplete="off" required="required" multiple="multiple"/>
                                                </div>
                                            </div>
                                    <?php }
                                } ?>
                                    <button type="submit" name="add" id="add" class="btn btn-info">Thêm </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>