<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet Edu - Student Management System | <?php echo $pageTitle; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Translate -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#4F46E5',
              secondary: '#10B981',
              dark: '#1F2937',
              light: '#F9FAFB',
            },
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
          }
        }
      }
      
      function googleTranslateElementInit() {
        new google.translate.TranslateElement(
          {pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 
          'google_translate_element'
        );
      }
    </script>
    
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
      
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        overflow-x: hidden;
      }
      
      .sidebar-nav li.active {
        background-color: rgba(79, 70, 229, 0.1);
        border-left: 4px solid #4F46E5;
      }
      
      .sidebar-nav li.active a {
        color: #4F46E5;
        font-weight: 500;
      }
      
      .gradient-bg {
        background: linear-gradient(135deg, #4F46E5 0%, #10B981 100%);
      }
      
      .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      }
      
      .transition-all {
        transition: all 0.3s ease;
      }
      
      /* Mobile menu styles */
      #mobileSidebar {
        transform: translateX(-100%);
      }
      #mobileSidebar.show {
        transform: translateX(0);
      }
      #mobileMenuOverlay {
        opacity: 0;
        pointer-events: none;
      }
      #mobileMenuOverlay.show {
        opacity: 1;
        pointer-events: auto;
      }
    </style>
  </head>
  
  <body class="bg-gray-50">
    <!-- Loading Screen -->
    <div id="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading ApexPlanet Edu System...</p>
        <p class="text-xs text-gray-400 mt-2">Powered by ApexPlanet Software Pvt Ltd</p>
      </div>
    </div>
    
    <div id="page" class="hidden">
      <!-- Mobile Menu Overlay -->
      <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity duration-300 lg:hidden"></div>
      
      <!-- Mobile Sidebar -->
      <aside id="mobileSidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-40 transform -translate-x-full transition-transform duration-300 lg:hidden">
        <div class="flex items-center justify-between p-4 border-b">
          <div class="flex items-center">
            <img src="assets/images/logo-icon.png" alt="ApexPlanet Edu" class="h-8">
          </div>
          <button id="mobileSidebarClose" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <?php include_once('includes/sidebar.php'); ?>
      </aside>

      <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-md fixed h-full z-20 hidden lg:block">
          <div class="p-4 border-b">
            <div class="flex items-center">
              <img src="assets/images/logo-icon.png" alt="ApexPlanet Edu" class="h-8">
              <span class="ml-2 text-xl font-semibold text-gray-800">ApexPlanet Edu</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Student Management System</p>
          </div>
          <?php include_once('includes/sidebar.php'); ?>
        </aside>

     <!-- Main Content -->      
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden lg:ml-64">
          <!-- Navbar -->
          <nav class="sticky top-0 z-10 bg-white shadow-sm">
            <div class="px-6 py-3 flex items-center justify-between">
              <div class="flex items-center">
                <button id="sidebarToggle" class="text-gray-500 focus:outline-none lg:hidden">
                  <i class="fas fa-bars"></i>
                </button>
                
                <div class="ml-4 flex items-center">
                  <img src="assets/images/logo-icon.png" alt="ApexPlanet Edu" class="h-8 w-auto">
                  <span class="ml-2 text-xl font-semibold text-gray-800">ApexPlanet Edu</span>
                </div>
              </div>
              
              <div class="flex items-center space-x-4">
                <!-- <div id="google_translate_element" class="mr-4"></div> -->
                
                <div class="relative">
                  <button class="flex items-center focus:outline-none" id="userMenuButton">
                    <?php
                    $aid = $_SESSION['sturecmsaid'];
                    $sql = "SELECT * from tbladmin where ID=:aid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':aid', $aid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    
                    if($query->rowCount() > 0) {
                      foreach($results as $row) {
                    ?>
                    <img class="h-8 w-8 rounded-full object-cover" src="assets/images/faces/face8.jpg" alt="Profile">
                    <span class="ml-2 text-sm font-medium text-gray-700 hidden md:inline"><?php echo htmlentities($row->AdminName); ?></span>
                    <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs"></i>
                    <?php }} ?>
                  </button>
                  
                  <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                    <div class="px-4 py-2 border-b">
                      <p class="text-sm font-medium text-gray-800"><?php echo htmlentities($row->AdminName); ?></p>
                      <p class="text-xs text-gray-500 truncate"><?php echo htmlentities($row->Email); ?></p>
                    </div>
                    <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-user mr-2 text-primary"></i> My Profile
                    </a>
                    <a href="change-password.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-key mr-2 text-primary"></i> Change Password
                    </a>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-sign-out-alt mr-2 text-primary"></i> Sign Out
                    </a>
                    <div class="px-4 py-2 border-t text-xs text-gray-500">
                      <p>Powered by ApexPlanet</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </nav>
          
          <main class="flex-1 pb-8">
            <div class="px-6 py-4">
              <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800"><?php echo $pageTitle; ?></h2>
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-500">Last updated: <?php echo date("F j, Y, g:i a"); ?></span>
                  <button class="p-2 text-gray-500 hover:text-primary focus:outline-none">
                    <i class="fas fa-sync-alt"></i>
                  </button>
                </div>
              </div>