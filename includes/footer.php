            </div>
          </main>
          
          <footer class="bg-white border-t py-4 px-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
              <div class="flex items-center">
                <img src="assets/images/apexplanet-logo.png" alt="ApexPlanet" class="h-6 mr-2">
                <p class="text-sm text-gray-600">© <?php echo date('Y'); ?> ApexPlanet Software Pvt Ltd. All rights reserved.</p>
              </div>
              
              <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-primary">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary">
                  <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary">
                  <i class="fab fa-github"></i>
                </a>
              </div>
            </div>
          </footer>
        </div>
      </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
      // Initialize animations
      AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
      });
      
      // Mobile menu functionality
      const sidebarToggle = document.getElementById('sidebarToggle');
      const mobileSidebar = document.getElementById('mobileSidebar');
      const mobileSidebarClose = document.getElementById('mobileSidebarClose');
      const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
      
      // Toggle mobile menu
      function toggleMobileMenu() {
        mobileSidebar.classList.toggle('show');
        mobileMenuOverlay.classList.toggle('show');
        document.body.classList.toggle('overflow-hidden');
      }
      
      // Event listeners
      sidebarToggle.addEventListener('click', toggleMobileMenu);
      mobileSidebarClose.addEventListener('click', toggleMobileMenu);
      mobileMenuOverlay.addEventListener('click', toggleMobileMenu);
      
      // Close menu when clicking on nav items
      document.querySelectorAll('#mobileSidebar a').forEach(link => {
        link.addEventListener('click', toggleMobileMenu);
      });
      
      // Toggle user dropdown
      document.getElementById('userMenuButton').addEventListener('click', function() {
        document.getElementById('userMenu').classList.toggle('hidden');
      });
      
      // Close dropdowns when clicking outside
      document.addEventListener('click', function(event) {
        // User dropdown
        if (!event.target.closest('#userMenuButton') && !event.target.closest('#userMenu')) {
          document.getElementById('userMenu').classList.add('hidden');
        }
      });
      
      // Loading screen
      function onReady(callback) {
        if (document.readyState !== 'loading') {
          callback();
        } else {
          document.addEventListener('DOMContentLoaded', callback);
        }
      }
      
      onReady(function() {
        setTimeout(function() {
          document.getElementById('loading').classList.add('hidden');
          document.getElementById('page').classList.remove('hidden');
        }, 800);
      });
      
      // Toggle submenus in sidebar
      const toggleMenu = (toggleId, menuId, iconId) => {
        const toggle = document.getElementById(toggleId);
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);
        
        toggle.addEventListener('click', () => {
          menu.classList.toggle('hidden');
          icon.classList.toggle('transform');
          icon.classList.toggle('rotate-180');
        });
      };
      
      // Initialize all sidebar submenus
      toggleMenu('classMenuToggle', 'classMenu', 'classMenuIcon');
      toggleMenu('studentMenuToggle', 'studentMenu', 'studentMenuIcon');
      toggleMenu('noticeMenuToggle', 'noticeMenu', 'noticeMenuIcon');
      toggleMenu('publicNoticeMenuToggle', 'publicNoticeMenu', 'publicNoticeMenuIcon');
    </script>
  </body>
</html>