<div class="overflow-y-auto h-full pb-20">
  <nav class="mt-6">
    <div class="px-4">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>
    </div>
    
    <ul class="mt-3 sidebar-nav">
      <li class="px-2 py-1 <?php echo $pageTitle == 'Dashboard' ? 'active' : ''; ?>">
        <a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
          <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>
          <span>Dashboard</span>
        </a>
      </li>
      
      <!-- Class Section -->
      <li class="px-2 py-1">
        <div class="flex items-center justify-between px-4 py-2 text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 transition-all" id="classMenuToggle">
          <div class="flex items-center">
            <i class="fas fa-layer-group mr-3 text-gray-400"></i>
            <span>Class Management</span>
          </div>
          <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="classMenuIcon"></i>
        </div>
        
        <ul class="pl-8 mt-1 hidden" id="classMenu">
          <li class="py-1 <?php echo $pageTitle == 'Add Class' ? 'active' : ''; ?>">
            <a href="add-class.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-plus-circle mr-2 text-xs"></i> Add Class
            </a>
          </li>
          <li class="py-1 <?php echo $pageTitle == 'Manage Classes' ? 'active' : ''; ?>">
            <a href="manage-class.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-tasks mr-2 text-xs"></i> Manage Classes
            </a>
          </li>
        </ul>
      </li>
      
      <!-- Student Section -->
      <li class="px-2 py-1">
        <div class="flex items-center justify-between px-4 py-2 text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 transition-all" id="studentMenuToggle">
          <div class="flex items-center">
            <i class="fas fa-users mr-3 text-gray-400"></i>
            <span>Student Management</span>
          </div>
          <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="studentMenuIcon"></i>
        </div>
        
        <ul class="pl-8 mt-1 hidden" id="studentMenu">
          <li class="py-1 <?php echo $pageTitle == 'Add Student' ? 'active' : ''; ?>">
            <a href="add-students.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-user-plus mr-2 text-xs"></i> Add Student
            </a>
          </li>
          <li class="py-1 <?php echo $pageTitle == 'Manage Students' ? 'active' : ''; ?>">
            <a href="manage-students.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-user-cog mr-2 text-xs"></i> Manage Students
            </a>
          </li>
        </ul>
      </li>
      
      <!-- Notice Section -->
      <li class="px-2 py-1">
        <div class="flex items-center justify-between px-4 py-2 text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 transition-all" id="noticeMenuToggle">
          <div class="flex items-center">
            <i class="fas fa-bell mr-3 text-gray-400"></i>
            <span>Class Notices</span>
          </div>
          <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="noticeMenuIcon"></i>
        </div>
        
        <ul class="pl-8 mt-1 hidden" id="noticeMenu">
          <li class="py-1 <?php echo $pageTitle == 'Add Notice' ? 'active' : ''; ?>">
            <a href="add-notice.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-plus-circle mr-2 text-xs"></i> Add Notice
            </a>
          </li>
          <li class="py-1 <?php echo $pageTitle == 'Manage Notices' ? 'active' : ''; ?>">
            <a href="manage-notice.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-tasks mr-2 text-xs"></i> Manage Notices
            </a>
          </li>
        </ul>
      </li>
      
      <!-- Public Notice Section -->
      <li class="px-2 py-1">
        <div class="flex items-center justify-between px-4 py-2 text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 transition-all" id="publicNoticeMenuToggle">
          <div class="flex items-center">
            <i class="fas fa-bullhorn mr-3 text-gray-400"></i>
            <span>Public Notices</span>
          </div>
          <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="publicNoticeMenuIcon"></i>
        </div>
        
        <ul class="pl-8 mt-1 hidden" id="publicNoticeMenu">
          <li class="py-1 <?php echo $pageTitle == 'Add Public Notice' ? 'active' : ''; ?>">
            <a href="add-public-notice.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-plus-circle mr-2 text-xs"></i> Add Public Notice
            </a>
          </li>
          <li class="py-1 <?php echo $pageTitle == 'Manage Public Notices' ? 'active' : ''; ?>">
            <a href="manage-public-notice.php" class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
              <i class="fas fa-tasks mr-2 text-xs"></i> Manage Public Notices
            </a>
          </li>
        </ul>
      </li>
      
      <!-- Other Menu Items -->
      <li class="px-2 py-1 <?php echo $pageTitle == 'Reports' ? 'active' : ''; ?>">
        <a href="between-dates-reports.php" class="flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
          <i class="fas fa-chart-bar mr-3 text-gray-400"></i>
          <span>Reports</span>
        </a>
      </li>
      
      <li class="px-2 py-1 <?php echo $pageTitle == 'Search' ? 'active' : ''; ?>">
        <a href="search.php" class="flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
          <i class="fas fa-search mr-3 text-gray-400"></i>
          <span>Search</span>
        </a>
      </li>
    </ul>
    
    <div class="px-4 mt-8">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Resources</p>
    </div>
    
    <ul class="mt-3">
      <li class="px-2 py-1">
        <a href="#" target="_blank" class="flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
          <i class="fas fa-info-circle mr-3 text-gray-400"></i>
          <span>Advance Version Info</span>
        </a>
      </li>
      
      <li class="px-2 py-1">
        <a href="#" target="_blank" class="flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-all">
          <i class="fas fa-video mr-3 text-gray-400"></i>
          <span>Advance Video Demo</span>
        </a>
      </li>
    </ul>
  </nav>
</div>

<div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t">
  <div class="flex items-center">
    <img src="assets/images/apexplanet-logo-icon.png" alt="ApexPlanet" class="h-6">
    <span class="ml-2 text-sm font-medium text-gray-600">ApexPlanet Software</span>
  </div>
  <p class="text-xs text-gray-500 mt-1">Premium Education Solutions</p>
</div>