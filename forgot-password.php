<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);
    
    $sql = "SELECT Email FROM tbladmin WHERE Email=:email AND MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        $con = "UPDATE tbladmin SET Password=:newpassword WHERE Email=:email AND MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        
        if($chngpwd1->execute()) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Password Changed!",
                    text: "Your password has been updated successfully",
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    window.location.href = "login.php";
                });
            </script>';
        }
    } else {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Invalid Credentials",
                text: "Email or mobile number is incorrect",
                showConfirmButton: true
            });
        </script>'; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <img class="w-20 h-20 mx-auto" src="images/logo.svg" alt="Logo">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Recover Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your email and mobile number to reset your password
                </p>
            </div>

            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                <form class="mb-0 space-y-6" method="post" name="chngpwd" onsubmit="return validateForm()">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="py-2 pl-10 block w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="your@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                            </div>
                            <input id="mobile" name="mobile" type="tel" required maxlength="10" pattern="[0-9]{10}"
                                class="py-2 pl-10 block w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="10-digit mobile number">
                        </div>
                    </div>

                    <div>
                        <label for="newpassword" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="newpassword" name="newpassword" type="password" required minlength="6"
                                class="py-2 pl-10 block w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="New password">
                        </div>
                    </div>

                    <div>
                        <label for="confirmpassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="confirmpassword" name="confirmpassword" type="password" required minlength="6"
                                class="py-2 pl-10 block w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Confirm password">
                        </div>
                    </div>

                    <div>
                        <button type="submit" name="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Password
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Remember your password?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in to your account
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="../index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-500">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            const newPassword = document.chngpwd.newpassword.value;
            const confirmPassword = document.chngpwd.confirmpassword.value;
            const mobile = document.chngpwd.mobile.value;
            
            // Password match validation
            if (newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'New Password and Confirm Password do not match!',
                    showConfirmButton: true
                });
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            
            // Password length validation
            if (newPassword.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Too Short',
                    text: 'Password must be at least 6 characters long',
                    showConfirmButton: true
                });
                return false;
            }
            
            // Mobile number validation
            if (!/^\d{10}$/.test(mobile)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Mobile Number',
                    text: 'Please enter a valid 10-digit mobile number',
                    showConfirmButton: true
                });
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>