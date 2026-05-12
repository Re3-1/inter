<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
{
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT ID FROM tbladmin WHERE UserName=:username and Password=:password";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        foreach ($results as $result) {
            $_SESSION['sturecmsaid']=$result->ID;
        }

        if(!empty($_POST["remember"])) {
            setcookie ("user_login",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
            setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
        } else {
            if(isset($_COOKIE["user_login"])) {
                setcookie ("user_login","");
                if(isset($_COOKIE["userpassword"])) {
                    setcookie ("userpassword","");
                }
            }
        }
        $_SESSION['login']=$_POST['username'];
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet Software Pvt Ltd | Admin Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style type="text/css">
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        }
        .login-card {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }
        .btn-hover:active {
            transform: translateY(0);
        }
        .apex-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Floating Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-purple-300 opacity-10 floating" style="animation-delay: 0s;"></div>
        <div class="absolute top-1/3 right-1/4 w-40 h-40 rounded-full bg-indigo-300 opacity-10 floating" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/4 right-1/3 w-28 h-28 rounded-full bg-blue-300 opacity-10 floating" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/3 left-1/4 w-36 h-36 rounded-full bg-violet-300 opacity-10 floating" style="animation-delay: 3s;"></div>
    </div>

    <!-- Main Login Card -->
    <div class="login-card bg-white/10 border border-white/20 rounded-2xl shadow-xl overflow-hidden w-full max-w-md animate__animated animate__fadeInUp">
        <div class="p-8">
            <!-- Logo Header -->
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center">
                  <img src="assets/images/logo-icon.png" alt="ApexPlanet Edu" class="h-8">
                  <span class="ml-2 text-xl font-semibold text-gray-800">ApexPlanet Edu</span>
                </div>
            </div>

            <!-- Login Form -->
            <form class="space-y-6" id="login" method="post" name="login">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-white">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-indigo-300"></i>
                        </div>
                        <input type="text" class="input-focus w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
                               placeholder="Enter username" required name="username" 
                               value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-white">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-indigo-300"></i>
                        </div>
                        <input type="password" class="input-focus w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
                               placeholder="Enter password" required name="password" 
                               value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-white/30 rounded" 
                               <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                        <label for="remember" class="ml-2 block text-sm text-white">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="forgot-password.php" class="font-medium text-indigo-200 hover:text-white transition-colors duration-200">Forgot password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" name="login" class="btn-hover w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-white/60">
                    &copy; <?php echo date("Y"); ?> ApexPlanet Software Pvt Ltd. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Add animation to form elements on focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('animate__animated', 'animate__pulse');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('animate__animated', 'animate__pulse');
            });
        });
    </script>
</body>
</html>