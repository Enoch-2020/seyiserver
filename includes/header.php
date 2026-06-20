<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
$path_prefix  = ($current_dir == 'admin') ? '../' : '';

$nav_links = [
    'index.php'     => 'Home',
    'About.php'     => 'About',
    'Services.php'  => 'Services',
    'Portfolio.php' => 'Portfolio',
    'Contact.php'   => 'Contact'
];

$current_file = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SeyiDev | Premium Tech Solutions</title>

  <!-- Core styles -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= $path_prefix ?>css/main.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet" />

  <!-- 3D & Animation libraries (deferred — run after parse) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" defer></script>
</head>

<body class="text-white overflow-x-hidden" style="background:#030014;">

  <!-- ===== PRELOADER ===== -->
  <div id="preloader">
    <div id="preloader-logo">Seyi<span>Dev.</span></div>
    <div id="preloader-bar-wrap"><div id="preloader-bar"></div></div>
    <p id="preloader-text">Initializing...</p>
  </div>

  <!-- ===== CUSTOM CURSOR (desktop only) ===== -->
  <div id="cursor-dot"></div>
  <div id="cursor-ring"></div>

  <!-- ===== NAVIGATION ===== -->
  <nav id="main-nav" class="glass-nav fixed w-full z-[100]">
    <div class="max-w-7xl mx-auto py-5 flex justify-between items-center px-6">

      <a href="<?= $path_prefix ?>index.php" class="text-2xl font-extrabold tracking-tighter">
        <span class="text-white">Seyi</span><span style="color:#a855f7;">Dev.</span>
      </a>

      <!-- Desktop links -->
      <div class="hidden md:flex items-center space-x-8 font-bold text-[10px] uppercase tracking-[0.2em]">
        <?php foreach ($nav_links as $file => $name): ?>
          <a href="<?= $path_prefix . $file ?>"
             class="nav-link-line <?= ($current_file === $file) ? 'text-purple-400 active' : 'text-zinc-400 hover:text-white'; ?> transition-colors duration-300">
            <?= $name ?>
          </a>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['admin_id'])): ?>
          <a href="<?= ($current_dir === 'admin') ? 'dashboard.php' : 'admin/dashboard.php' ?>"
             class="ml-4 px-6 py-2.5 bg-white text-black rounded-full hover:bg-purple-600 hover:text-white transition font-bold">
            Dashboard
          </a>
        <?php else: ?>
          <a href="<?= $path_prefix ?>Contact.php"
             class="ml-4 px-6 py-2.5 rounded-full font-bold transition"
             style="background:#a855f7;color:#fff;box-shadow:0 0 24px rgba(168,85,247,0.35);">
            Hire Us
          </a>
        <?php endif; ?>
      </div>

      <!-- Hamburger -->
      <button id="menu-btn" class="md:hidden flex flex-col gap-1.5 p-2 focus:outline-none" aria-label="Open menu">
        <span class="ham-line"></span>
        <span class="ham-line" style="width:20px;"></span>
        <span class="ham-line"></span>
      </button>
    </div>
  </nav>

  <!-- ===== MOBILE MENU PANEL ===== -->
  <!--
    Key layout fixes:
    - background is explicit inline style (never depends on Tailwind CDN)
    - overflow-x:hidden + overflow-y:auto  →  content never gets clipped, scrollable if needed
    - flex-shrink:0 on header & footer    →  they never collapse
    - min-height:0 on nav                 →  flex child can shrink so footer stays visible
    - clamp() font sizes                  →  scales down on narrow / short screens
  -->
  <div id="mobile-menu"
       role="dialog"
       aria-modal="true"
       aria-label="Navigation"
       style="position:fixed;top:0;right:0;bottom:0;left:0;
              z-index:300;
              background:#030014;
              pointer-events:none;
              transform:translateX(100%);
              transition:transform 0.55s cubic-bezier(0.76,0,0.24,1);
              overflow-x:hidden;
              overflow-y:auto;
              -webkit-overflow-scrolling:touch;
              display:flex;
              flex-direction:column;
              box-sizing:border-box;">

    <!-- Decorative blobs — position:absolute, translate only outward (H), never downward past bottom -->
    <div style="position:absolute;top:0;right:0;width:280px;height:280px;
                background:rgba(124,58,237,0.15);border-radius:50%;
                filter:blur(70px);pointer-events:none;z-index:0;
                transform:translate(25%,-25%);"></div>
    <div style="position:absolute;bottom:0;left:0;width:220px;height:220px;
                background:rgba(168,85,247,0.08);border-radius:50%;
                filter:blur(55px);pointer-events:none;z-index:0;
                transform:translate(-25%,-25%);"></div>

    <!-- ── Top bar (never shrinks) ── -->
    <div style="flex-shrink:0;
                display:flex;justify-content:space-between;align-items:center;
                padding:clamp(1rem,3.5vw,1.5rem) clamp(1.25rem,5vw,2rem);
                position:relative;z-index:10;
                border-bottom:1px solid rgba(255,255,255,0.07);">

      <a href="<?= $path_prefix ?>index.php"
         style="font-size:clamp(1.25rem,4vw,1.5rem);font-weight:900;
                letter-spacing:-0.05em;color:#fff;text-decoration:none;line-height:1;">
        Seyi<span style="color:#a855f7;">Dev.</span>
      </a>

      <button id="close-menu"
              style="width:40px;height:40px;flex-shrink:0;border-radius:50%;
                     border:1px solid rgba(168,85,247,0.3);
                     background:rgba(168,85,247,0.1);color:#a855f7;
                     font-size:1.1rem;
                     display:flex;align-items:center;justify-content:center;
                     cursor:pointer;transition:background 0.3s,transform 0.4s;outline:none;"
              aria-label="Close menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- ── Nav links (min-height:0 lets it shrink; flex:1 fills space) ── -->
    <nav style="flex:1;min-height:0;
                display:flex;flex-direction:column;justify-content:center;
                padding:0 clamp(1.25rem,5vw,2rem);
                position:relative;z-index:10;">

      <?php
      $num = 1;
      foreach ($nav_links as $file => $name):
        $isActive = $current_file === $file;
      ?>
        <a href="<?= $path_prefix . $file ?>"
           class="menu-link<?= $isActive ? ' menu-link--active' : '' ?>"
           style="display:flex;align-items:center;justify-content:space-between;
                  padding:clamp(0.65rem,2.2vh,1rem) 0;
                  border-bottom:1px solid rgba(255,255,255,0.05);
                  text-decoration:none;
                  opacity:0;
                  transform:translateX(48px);
                  transition:opacity 0.5s cubic-bezier(0.23,1,0.32,1),
                              transform 0.5s cubic-bezier(0.23,1,0.32,1);
                  -webkit-tap-highlight-color:transparent;">

          <!-- Number + name -->
          <div style="min-width:0;">
            <span style="display:block;font-size:8px;font-weight:800;
                         letter-spacing:0.4em;text-transform:uppercase;
                         color:<?= $isActive ? '#a855f7' : '#3f3f46' ?>;
                         margin-bottom:2px;line-height:1;">
              0<?= $num ?>
            </span>
            <span style="display:block;
                         font-size:clamp(1.5rem,6.5vw,2.2rem);
                         font-weight:900;letter-spacing:-0.04em;
                         text-transform:uppercase;line-height:1;
                         color:<?= $isActive ? '#a855f7' : '#ffffff' ?>;
                         white-space:nowrap;">
              <?= $name ?>
            </span>
          </div>

          <!-- Arrow -->
          <span style="font-size:clamp(1.1rem,3.5vw,1.4rem);
                       color:<?= $isActive ? '#a855f7' : '#3f3f46' ?>;
                       flex-shrink:0;margin-left:1rem;
                       transition:color 0.25s,transform 0.25s;">
            <?= $isActive ? '↗' : '→' ?>
          </span>
        </a>
      <?php $num++; endforeach; ?>

    </nav>

    <!-- ── Footer (never shrinks) ── -->
    <div id="menu-footer-section"
         style="flex-shrink:0;
                padding:clamp(1rem,3.5vw,1.5rem) clamp(1.25rem,5vw,2rem);
                position:relative;z-index:10;
                border-top:1px solid rgba(255,255,255,0.07);">

      <?php if (isset($_SESSION['admin_id'])): ?>
        <a href="<?= ($current_dir === 'admin') ? 'dashboard.php' : 'admin/dashboard.php' ?>"
           class="menu-footer-cta"
           style="display:flex;align-items:center;justify-content:center;
                  padding:0.85rem 1.5rem;border-radius:100px;
                  font-weight:900;font-size:10px;
                  letter-spacing:0.2em;text-transform:uppercase;text-decoration:none;
                  color:#000;background:#fff;
                  margin-bottom:1.25rem;
                  opacity:0;transform:translateY(16px);
                  transition:opacity 0.45s,transform 0.45s;">
          Dashboard
        </a>
      <?php else: ?>
        <a href="<?= $path_prefix ?>Contact.php"
           class="menu-footer-cta"
           style="display:flex;align-items:center;justify-content:center;gap:0.4rem;
                  padding:0.85rem 1.5rem;border-radius:100px;
                  font-weight:900;font-size:10px;
                  letter-spacing:0.2em;text-transform:uppercase;text-decoration:none;
                  color:#fff;margin-bottom:1.25rem;
                  background:linear-gradient(135deg,#7c3aed,#a855f7);
                  box-shadow:0 0 24px rgba(168,85,247,0.3);
                  opacity:0;transform:translateY(16px);
                  transition:opacity 0.45s,transform 0.45s;">
          Get Started &nbsp;↗
        </a>
      <?php endif; ?>

      <!-- Social links -->
      <div id="menu-socials"
           style="display:flex;justify-content:center;gap:1.5rem;
                  margin-bottom:0.75rem;
                  opacity:0;transition:opacity 0.4s;">
        <a href="#"
           style="color:#3f3f46;font-size:1.1rem;
                  transition:color 0.25s;-webkit-tap-highlight-color:transparent;"
           onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#3f3f46'">
          <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="#"
           style="color:#3f3f46;font-size:1.1rem;
                  transition:color 0.25s;-webkit-tap-highlight-color:transparent;"
           onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#3f3f46'">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="https://www.linkedin.com/in/kazeem-kabiru-477abbb6/" target="_blank"
           style="color:#3f3f46;font-size:1.1rem;
                  transition:color 0.25s;-webkit-tap-highlight-color:transparent;"
           onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#3f3f46'">
          <i class="fa-brands fa-linkedin-in"></i>
        </a>
      </div>

      <p id="menu-tagline"
         style="text-align:center;font-size:8px;letter-spacing:0.45em;
                text-transform:uppercase;color:#27272a;
                opacity:0;transition:opacity 0.4s;">
        Innovating Since 2024
      </p>
    </div>

  </div>

  <!-- ===== MOBILE MENU JS ===== -->
  <script>
  (function() {
    var menuBtn   = document.getElementById('menu-btn');
    var closeBtn  = document.getElementById('close-menu');
    var menu      = document.getElementById('mobile-menu');
    var links     = menu.querySelectorAll('.menu-link');
    var cta       = menu.querySelector('.menu-footer-cta');
    var socials   = document.getElementById('menu-socials');
    var tagline   = document.getElementById('menu-tagline');
    var hamLines  = document.querySelectorAll('.ham-line');
    var isOpen    = false;

    function openMenu() {
      if (isOpen) return;
      isOpen = true;
      menu.scrollTop = 0;                           /* always start at top */
      menu.style.transform     = 'translateX(0)';
      menu.style.pointerEvents = 'auto';
      document.body.style.overflow             = 'hidden';
      document.documentElement.style.overflow  = 'hidden'; /* lock <html> too (iOS) */

      /* Stagger nav links in */
      links.forEach(function(link, i) {
        setTimeout(function() {
          link.style.opacity   = '1';
          link.style.transform = 'translateX(0)';
        }, 100 + i * 75);
      });
      /* CTA + socials + tagline — slightly delayed so links settle first */
      setTimeout(function() {
        if (cta)    { cta.style.opacity = '1'; cta.style.transform = 'translateY(0)'; }
        if (socials)  socials.style.opacity  = '1';
        if (tagline)  tagline.style.opacity  = '1';
      }, 100 + links.length * 75 + 60);

      /* Hamburger → X */
      hamLines[0].style.transform = 'translateY(7.5px) rotate(45deg)';
      hamLines[1].style.opacity   = '0';
      hamLines[2].style.transform = 'translateY(-7.5px) rotate(-45deg)';
    }

    function closeMenu() {
      if (!isOpen) return;
      isOpen = false;
      menu.style.transform     = 'translateX(100%)';
      menu.style.pointerEvents = 'none';
      document.body.style.overflow             = '';
      document.documentElement.style.overflow  = '';

      /* Reset link states */
      links.forEach(function(link) {
        link.style.opacity   = '0';
        link.style.transform = 'translateX(48px)';
      });
      if (cta)    { cta.style.opacity = '0'; cta.style.transform = 'translateY(16px)'; }
      if (socials)  socials.style.opacity  = '0';
      if (tagline)  tagline.style.opacity  = '0';

      /* X → Hamburger */
      hamLines[0].style.transform = '';
      hamLines[1].style.opacity   = '1';
      hamLines[2].style.transform = '';
    }

    menuBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);

    /* Close on link click */
    links.forEach(function(link) {
      link.addEventListener('click', closeMenu);
    });

    /* Close on Escape */
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeMenu();
    });

    /* Nav scroll tint */
    window.addEventListener('scroll', function() {
      document.getElementById('main-nav')
        .classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });
  })();
  </script>

  <!-- ===== PRELOADER JS ===== -->
  <script>
  (function() {
    var bar      = document.getElementById('preloader-bar');
    var loader   = document.getElementById('preloader');
    var pText    = document.getElementById('preloader-text');
    var progress = 0;
    var msgs     = ['Initializing...','Loading assets...','Building UI...','Almost ready...'];
    var mi = 0;

    var iv = setInterval(function() {
      progress += Math.random() * 16 + 5;
      if (progress >= 100) { progress = 100; clearInterval(iv); }
      bar.style.width = progress + '%';
      if (progress > (mi + 1) * 25 && mi < msgs.length - 1) pText.textContent = msgs[++mi];
    }, 120);

    window.addEventListener('load', function() {
      clearInterval(iv);
      bar.style.width = '100%';
      setTimeout(function() {
        loader.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        loader.style.opacity    = '0';
        loader.style.transform  = 'translateY(-16px)';
        setTimeout(function() { loader.style.display = 'none'; }, 700);
      }, 350);
    });
  })();
  </script>

  <!-- ===== CURSOR JS (desktop only) ===== -->
  <script>
  (function() {
    if (window.matchMedia('(hover:none)').matches) return; /* Skip on touch devices */
    var dot  = document.getElementById('cursor-dot');
    var ring = document.getElementById('cursor-ring');
    if (!dot || !ring) return;

    var mx = 0, my = 0, rx = 0, ry = 0;

    document.addEventListener('mousemove', function(e) {
      mx = e.clientX; my = e.clientY;
      dot.style.left = mx + 'px';
      dot.style.top  = my + 'px';
    });

    (function animRing() {
      rx += (mx - rx) * 0.1;
      ry += (my - ry) * 0.1;
      ring.style.left = rx + 'px';
      ring.style.top  = ry + 'px';
      requestAnimationFrame(animRing);
    })();

    document.addEventListener('mouseover', function(e) {
      if (e.target && e.target.closest('a, button, [data-cursor]'))
        document.body.classList.add('cursor-grow');
    });
    document.addEventListener('mouseout', function(e) {
      if (e.target && e.target.closest('a, button, [data-cursor]'))
        document.body.classList.remove('cursor-grow');
    });
    document.addEventListener('mousedown', function() { dot.style.transform='translate(-50%,-50%) scale(0.5)'; });
    document.addEventListener('mouseup',   function() { dot.style.transform='translate(-50%,-50%) scale(1)';   });
  })();
  </script>
