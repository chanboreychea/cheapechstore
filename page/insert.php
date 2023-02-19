<?php
error_reporting(0);

$msg = "";
//edit
if (isset($_POST['edit'])) {
	$product_id = $_POST['edit'];
	try {





	} catch (PDOException $e) {
		echo $e->getMessage();
	}
}
//delete
if (isset($_POST["remove"])) {
	$product_id = $_POST['remove'];
	try {
		$db = mysqli_connect("localhost", "root", "", "_cloths");
		$queryimg = "SELECT image_front, image_back from products where product_id = $product_id";
		$result = $db->query($queryimg);
		$row = $result->fetch_assoc();
		unlink('../image/' . $row['image_front']);
		unlink('../image/' . $row['image_back']);

		// Get all the submitted data from the form
		$sql = "DELETE FROM products where product_id = $product_id";

		// Execute query
		mysqli_query($db, $sql);
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
}
// If upload button is clicked ...
if (isset($_POST['upload'])) {

	$product_name = $_POST['product_name'];
	$qty = $_POST['qty'];
	$price = $_POST['price'];

	// $image_front = $_FILES["image_front"]["name"];
	$tempname = $_FILES["image_front"]["tmp_name"];
	$extension = explode('.', $_FILES["image_front"]["name"]);
	$extension = end($extension);
	$image_front = "../image/" . time() . '_' . md5(rand()) . '.' . $extension;

	// $image_back = $_FILES["image_back"]["name"];
	$tempnamee = $_FILES["image_back"]["tmp_name"];
	$extensionn = explode('.', $_FILES["image_back"]["name"]);
	$extensionn = end($extensionn);
	$image_back = "../image/" . time() . '_' . md5(rand()) . '.' . $extensionn;

	$categorie = $_POST['categorie'];

	$db = mysqli_connect("localhost", "root", "", "_cloths");

	// Get all the submitted data from the form
	$sql = "INSERT INTO products (product_name, qty, price,image_front, image_back, categorie_id) VALUES ('$product_name','$qty','$price','$image_front','$image_back','$categorie')";

	// Execute query
	mysqli_query($db, $sql);

	// Now let's move the uploaded image into the folder: image
	if (move_uploaded_file($tempname, $image_front)) {
		if (move_uploaded_file($tempnamee, $image_back)) {
			$alert = "<h3> uploaded successfully!</h3>";
		} else {
			$alert = "<h3> Failed to upload!</h3>";
		}
	} else {
		$alert = "<h3> Failed to upload!</h3>";
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Image Upload</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/insert.css" />
</head>

<body>
	<div class="header">
		<div class="blank"></div>
		<div class="logo">
			<div class="box-logo">
				<img src="../image/photo_2022-02-04_20-39-23.jpg">
			</div>
		</div>
		<div class="cart"></div>
	</div>
	<div class="wrapper">
		<div class="content-insert">
			<form method="POST" action="" enctype="multipart/form-data">
				<?php
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "_cloths";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT categorie_id, categorie_name  FROM categories";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
				?>
					<label for="exampleFormControlSelect1">Products Categories</label>
					<select class="form-control" id="exampleFormControlSelect1" name="categorie">
						<?php while ($row = $result->fetch_assoc()) { ?>
							<option value="<?php echo $row['categorie_id'] ?>"> <?php echo $row['categorie_name'] ?></option>
						<?php }
						?>
					</select>
				<?php }
				?>
				<br>
				<div class="form-group">
					<input class="form-control" type="text" name="product_name" value="" placeholder="Product Name" />
				</div>
				<div class="form-group">
					<input class="form-control" type="number" name="qty" value="" placeholder="Quantity" />
				</div>
				<div class="form-group">
					<input class="form-control" type="text" name="price" value="" placeholder="Price" />
				</div>
				<div class="form-group">
					<label for="image_front">Image Front</label>
					<input class="form-control" type="file" name="image_front" value="" id="image_front" />
				</div>
				<div class="form-group">
					<label for="image_back">Image Back</label>
					<input class="form-control" type="file" name="image_back" value="" id="image_back" />
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit" name="upload">UPLOAD</button>
				</div>
			</form>
		</div>
		<div class="content-read">
			<?php
			$cn = mysqli_connect("localhost", "root", "", "_cloths");
			if (mysqli_connect_errno() > 0) {
				die(mysqli_connect_error());
			}
			$sql = "SELECT product_id, product_name, qty, price,image_front, image_back FROM products";
			$result = mysqli_query($cn, $sql);
			if (mysqli_errno($cn) > 0) {
				die(mysqli_error($cn));
			}
			?>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">Product Name</th>
						<th scope="col">Quantity</th>
						<th scope="col">Price</th>
						<th scope="col">Image Front</th>
						<th scope="col">Image Back</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($student = mysqli_fetch_assoc($result)) { ?>
						<tr>

							<th><?php echo $student["product_name"]; ?></th>
							<th><?php echo $student["qty"]; ?></th>
							<th><?php echo $student["price"]; ?></th>
							<th>
								<div class="image"><img src="../image/<?php echo $student["image_front"]; ?>" alt=""></div>
							</th>
							<th>
								<div class="image"><img src="../image/<?php echo $student["image_back"]; ?>" alt=""></div>
							</th>
							<th>
								<form method="POST" action="" enctype="multipart/form-data">
									<button type="submit" name="edit" value="<?php echo $student['product_id'] ?>" class="btn btn-primary">Edit</button>
									<button type="submit" name="remove" value="<?php echo $student['product_id'] ?>" class="btn btn-danger">Remove</button>
								</form>
							</th>

						</tr>
					<?php } ?>
					<?php mysqli_close($cn); ?>
			</table>
		</div>
	</div>

</body>

</html>