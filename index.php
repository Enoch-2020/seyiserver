<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$portfolio_stmt = $pdo->query("SELECT title, category, description, feature_image, link, about_product FROM services ORDER BY id DESC LIMIT 6");
$portfolios = $portfolio_stmt->fetchAll();

$client_stmt = $pdo->query("SELECT client_name, client_logo_url, website_url FROM clients WHERE is_active = 1");
$clients = $client_stmt->fetchAll();
?>

<?php if (isset($_GET['status'])): $isSuccess = $_GET['status'] === 'success'; ?>
<div id="status-popup" class="fixed top-10 left-1/2 -translate-x-1/2 md:left-auto md:right-10 md:translate-x-0 z-[9999] animate-popup">
  <div class="relative overflow-hidden min-w-[340px] backdrop-blur-2xl bg-zinc-950/90 border <?= $isSuccess ? 'border-green-500/30' : 'border-red-500/30' ?> p-6 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
    <div class="absolute -inset-1 <?= $isSuccess ? 'bg-green-500/10' : 'bg-red-500/10' ?> blur-2xl"></div>
    <div class="relative flex items-center gap-5">
      <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center <?= $isSuccess ? 'bg-green-500' : 'bg-red-500' ?> text-white shadow-lg">
        <?php if ($isSuccess): ?>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        <?php else: ?>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
        <?php endif; ?>
      </div>
      <div>
        <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-1"><?= $isSuccess ? 'Transmission Received' : 'Transmission Failed' ?></h5>
        <p class="text-slate-300 text-sm font-medium leading-tight"><?= $isSuccess ? 'Your brief is in the vault.<br>Reviewing now.' : 'Something went wrong.<br>Please try again.' ?></p>
      </div>
    </div>
    <div class="absolute bottom-0 left-0 h-1.5 <?= $isSuccess ? 'bg-green-500' : 'bg-red-500' ?> animate-progress"></div>
  </div>
</div>
<script>
  setTimeout(() => {
    const p = document.getElementById('status-popup');
    if (p) { p.style.transition='all 0.6s'; p.style.opacity='0'; setTimeout(()=>p.remove(),600); }
  }, 5000);
</script>
<?php endif; ?>


<!-- ============================================================
     HERO SECTION — THREE.JS PARTICLE CANVAS
     ============================================================ -->
<section class="relative min-h-screen flex flex-col justify-end overflow-hidden" style="background:#030014;">

  <!-- Three.js Canvas -->
  <canvas id="heroCanvas" class="absolute inset-0 w-full h-full" style="opacity:0.6;"></canvas>

  <!-- Grid texture overlay -->
  <div class="absolute inset-0 pointer-events-none grid-texture" style="opacity:0.25;"></div>

  <!-- Gradient overlays -->
  <div class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-[#030014] to-transparent pointer-events-none"></div>
  <div class="absolute inset-x-0 bottom-0 h-72 bg-gradient-to-t from-[#030014] to-transparent pointer-events-none"></div>
  <div class="absolute inset-y-0 left-0 w-2/3 bg-gradient-to-r from-[#030014] via-[#030014]/60 to-transparent pointer-events-none"></div>

  <!-- Glow orbs -->
  <div class="absolute top-1/4 left-1/3 w-[700px] h-[700px] bg-purple-700/10 blur-[180px] rounded-full pointer-events-none animate-blob"></div>
  <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-indigo-600/8 blur-[120px] rounded-full pointer-events-none animate-blob animation-delay-4000"></div>

  <!-- Content -->
  <div class="section-container relative z-10 pb-20 pt-36">

    <!-- Badge -->
    <div class="badge mb-10 hero-reveal">
      <span class="dot"></span>
      Software Architecture &amp; Engineering
    </div>

    <!-- Massive headline -->
    <div class="mb-14" style="animation-delay:0.15s">
      <div class="overflow-hidden">
        <h1 class="hero-reveal text-white" style="font-size:clamp(3.2rem,9vw,9rem);font-weight:900;line-height:0.92;letter-spacing:-0.04em;animation-delay:0.05s">
          We Build
        </h1>
      </div>
      <div class="overflow-hidden">
        <h1 class="hero-reveal gradient-text" style="font-size:clamp(3.2rem,9vw,9rem);font-weight:900;line-height:0.92;letter-spacing:-0.04em;animation-delay:0.15s">
          Digital
        </h1>
      </div>
      <div class="overflow-hidden">
        <h1 class="hero-reveal text-white" style="font-size:clamp(3.2rem,9vw,9rem);font-weight:900;line-height:0.92;letter-spacing:-0.04em;animation-delay:0.25s">
          Experiences.
        </h1>
      </div>
    </div>

    <!-- Description + CTAs + Stats -->
    <div class="flex flex-col lg:flex-row lg:items-end gap-12 lg:gap-24">

      <!-- Left side -->
      <div class="hero-reveal" style="animation-delay:0.4s">
        <p class="text-slate-400 leading-relaxed max-w-md mb-8" style="font-size:1rem;">
          Transforming complex ideas into high-performance digital products — from custom CMS to full-stack systems, engineered for real impact.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="Portfolio.php" class="btn-primary magnetic">View Our Work <span>↗</span></a>
          <a href="Contact.php"   class="btn-secondary magnetic">Start a Project</a>
        </div>
      </div>

      <!-- Stats -->
      <div class="flex gap-10 lg:gap-16 lg:ml-auto shrink-0 hero-reveal" style="animation-delay:0.5s">
        <div>
          <div class="text-5xl lg:text-6xl font-black text-white leading-none tabular-nums">
            <span class="counter" data-target="50">0</span>+
          </div>
          <div class="text-zinc-500 text-[10px] uppercase tracking-[0.3em] font-bold mt-2">Projects Done</div>
        </div>
        <div>
          <div class="text-5xl lg:text-6xl font-black text-white leading-none tabular-nums">
            <span class="counter" data-target="5">0</span>+
          </div>
          <div class="text-zinc-500 text-[10px] uppercase tracking-[0.3em] font-bold mt-2">Years Active</div>
        </div>
        <div>
          <div class="text-5xl lg:text-6xl font-black text-white leading-none tabular-nums">
            <span class="counter" data-target="98">0</span>%
          </div>
          <div class="text-zinc-500 text-[10px] uppercase tracking-[0.3em] font-bold mt-2">Satisfaction</div>
        </div>
      </div>

    </div>
  </div>

  <!-- Floating UI cards — top right (desktop only) -->
  <div class="absolute top-32 right-6 lg:right-16 z-10 flex-col gap-5 hidden lg:flex">

    <div class="animate-float glass-ui-card p-4 rounded-2xl" style="animation-delay:0s">
      <div class="flex gap-1.5 mb-2.5">
        <div class="w-2 h-2 rounded-full bg-red-400/60"></div>
        <div class="w-2 h-2 rounded-full bg-yellow-400/60"></div>
        <div class="w-2 h-2 rounded-full bg-green-400/60"></div>
      </div>
      <code class="text-[10px] font-mono text-purple-300">$ npm install success</code>
    </div>

    <div class="animate-float-2 glass-ui-card p-5 rounded-2xl ml-8">
      <div class="flex items-center gap-2 mb-3">
        <div class="bg-blue-500 text-[10px] font-bold px-1.5 py-0.5 rounded text-white">TS</div>
        <span class="text-[9px] text-slate-400 font-mono uppercase tracking-widest">Type Definition</span>
      </div>
      <div class="font-mono text-[11px] leading-tight">
        <span class="text-blue-400">type</span> <span class="text-yellow-300">SeyiDev</span> = {<br>
        &nbsp;&nbsp;role: <span class="text-green-400 italic" id="typewriter"></span><span class="text-white animate-blink">|</span><br>
        };
      </div>
    </div>

    <div class="animate-float glass-ui-card p-4 rounded-2xl flex items-center gap-3" style="animation-delay:2s">
      <div class="w-8 h-8 rounded-full bg-green-500/15 border border-green-500/30 flex items-center justify-center shrink-0">
        <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
      </div>
      <div>
        <div class="text-[10px] font-bold text-white">System Online</div>
        <div class="text-[9px] text-zinc-500">100% Uptime</div>
      </div>
    </div>

  </div>

  <!-- Scroll indicator -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-zinc-600 hero-reveal" style="animation-delay:0.8s">
    <span class="text-[9px] uppercase tracking-[0.4em] font-bold">Scroll</span>
    <div class="w-px h-10 bg-gradient-to-b from-purple-500/50 to-transparent"></div>
  </div>
</section>


<!-- ============================================================
     CAPABILITIES SECTION
     ============================================================ -->
<section class="py-32 px-5 relative overflow-hidden" style="background:#050010;">
  <div class="absolute inset-0 grid-texture pointer-events-none" style="opacity:0.15;"></div>
  <div class="absolute -top-32 right-0 w-[600px] h-[600px] bg-purple-800/10 blur-[150px] rounded-full pointer-events-none"></div>

  <div class="section-container">

    <div class="flex flex-col lg:flex-row lg:items-end gap-8 mb-20">
      <div>
        <div class="badge mb-6" data-aos="fade-right" data-aos-duration="700"><span class="dot"></span>Our Capabilities</div>
        <div class="reveal-line mb-5" data-aos="fade-right" data-aos-delay="100"></div>
        <div class="clip-reveal">
          <h2 class="clip-reveal-inner text-white" style="font-size:clamp(2.5rem,5vw,4.5rem);font-weight:900;line-height:1;letter-spacing:-0.03em;">
            What We<br><span class="gradient-text">Do Best.</span>
          </h2>
        </div>
      </div>
      <p class="text-slate-500 text-sm lg:max-w-xs lg:ml-auto pb-2" data-aos="fade-up" data-aos-delay="200">
        Everything you need to build, scale, and maintain a powerful digital presence.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php
      $services = [
        ['icon'=>'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
         'title'=>'Plugin Dev','desc'=>'Custom WordPress plugins built for performance, security, and infinite scalability.'],
        ['icon'=>'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
         'title'=>'Frontend Magic','desc'=>'Stunning, responsive interfaces using React, Tailwind CSS, and cutting-edge modern patterns.'],
        ['icon'=>'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2',
         'title'=>'Backend Logic','desc'=>'Rock-solid server-side systems and API integrations engineered to never fail under pressure.'],
        ['icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
         'title'=>'Website Redesign','desc'=>'Transforming outdated legacy websites into modern, high-converting digital experiences.'],
        ['icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
         'title'=>'Tech Training','desc'=>'Empowering your team with the skills to confidently manage and grow their own platforms.'],
        ['icon'=>'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z',
         'title'=>'IT Strategy','desc'=>'Expert advisory to align your technology stack with your core business objectives.'],
      ];
      foreach ($services as $i => $s): ?>

      <div class="service-card" data-tilt data-tilt-max="8" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.15"
           data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $s['icon'] ?>" />
          </svg>
        </div>
        <div class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600 mb-3"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
        <h3 class="text-xl font-bold text-white mb-3"><?= $s['title'] ?></h3>
        <p class="text-slate-500 text-sm leading-relaxed"><?= $s['desc'] ?></p>
        <div class="mt-6 flex items-center gap-2 text-purple-500 text-xs font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
          <span>Explore</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
      </div>

      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============================================================
     PROCESS SECTION
     ============================================================ -->
<section class="py-32 px-5 relative overflow-hidden bg-black">
  <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-purple-500/20 to-transparent"></div>
  <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-purple-500/20 to-transparent"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-purple-900/5 blur-[200px] rounded-full pointer-events-none"></div>

  <div class="section-container">
    <div class="flex flex-col lg:flex-row lg:items-end gap-8 mb-20">
      <div>
        <div class="badge mb-6" data-aos="fade-right" data-aos-duration="700"><span class="dot"></span>How We Work</div>
        <div class="reveal-line mb-5" data-aos="fade-right" data-aos-delay="100"></div>
        <div class="clip-reveal">
          <h2 class="clip-reveal-inner text-white" style="font-size:clamp(2.5rem,5vw,4.5rem);font-weight:900;line-height:1;letter-spacing:-0.03em;">
            The <span class="gradient-text">Process.</span>
          </h2>
        </div>
      </div>
      <p class="text-slate-500 text-sm lg:max-w-xs lg:ml-auto pb-2" data-aos="fade-up" data-aos-delay="200">
        A proven four-step framework delivering exceptional results on every engagement.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

      <?php
      $steps = [
        ['num'=>'01','title'=>'Discovery','desc'=>"We deep-dive into your vision, audience, and goals before writing a single line of code. Strategy first."],
        ['num'=>'02','title'=>'Design','desc'=>"Crafting wireframes and high-fidelity prototypes that align pixel-perfect beauty with measurable UX outcomes."],
        ['num'=>'03','title'=>'Development','desc'=>"Clean, scalable code built with modern architecture. No shortcuts — every component is tested and optimized."],
        ['num'=>'04','title'=>'Delivery','desc'=>"Launch, monitor, and iterate. We stay involved post-launch to ensure everything runs flawlessly at scale."],
      ];
      foreach ($steps as $i => $step): ?>

      <div class="process-card" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
        <div class="process-number"><?= $step['num'] ?></div>
        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center mb-6">
          <span class="text-purple-400 font-black text-sm"><?= $step['num'] ?></span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3"><?= $step['title'] ?></h3>
        <p class="text-slate-500 text-sm leading-relaxed"><?= $step['desc'] ?></p>
      </div>

      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============================================================
     PORTFOLIO SECTION
     ============================================================ -->
<section id="recent-portfolio" class="py-32 relative overflow-hidden" style="background:#030014;">
  <div class="absolute inset-0 grid-texture pointer-events-none" style="opacity:0.1;"></div>
  <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-purple-600/8 blur-[150px] rounded-full pointer-events-none"></div>
  <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-600/5 blur-[120px] rounded-full pointer-events-none"></div>

  <div class="section-container px-5 relative z-10">
    <div class="flex flex-col lg:flex-row lg:items-end gap-8 mb-24">
      <div>
        <div class="badge mb-6" data-aos="fade-right" data-aos-duration="700"><span class="dot"></span>Featured Work</div>
        <div class="reveal-line mb-5" data-aos="fade-right" data-aos-delay="100"></div>
        <div class="clip-reveal">
          <h2 class="clip-reveal-inner text-white" style="font-size:clamp(2.5rem,6vw,6rem);font-weight:900;line-height:0.95;letter-spacing:-0.04em;">
            Featured<br><span class="gradient-text-pink">Creations.</span>
          </h2>
        </div>
      </div>
      <p class="text-slate-500 text-sm lg:max-w-xs lg:ml-auto pb-2" data-aos="fade-up" data-aos-delay="200">
        A curated selection of our latest digital products, crafted with precision and engineered for impact.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
      <?php foreach ($portfolios as $index => $work): ?>
        <div class="group relative" data-tilt data-tilt-max="5" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.1"
             data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">

          <!-- Glow border on hover -->
          <div class="absolute -inset-px bg-gradient-to-br from-purple-600/30 to-blue-600/30 rounded-3xl opacity-0 group-hover:opacity-100 transition-all duration-500 blur-sm"></div>

          <div class="relative bg-zinc-900/40 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/5 group-hover:border-purple-500/20 transition-all duration-500">
            <div class="relative h-64 overflow-hidden">
              <img src="<?= htmlspecialchars($work['feature_image']) ?>"
                   class="w-full h-full object-cover grayscale-[50%] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700"
                   onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80'" />
              <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/30 to-transparent"></div>
              <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                <span class="badge" style="font-size:9px;padding:0.3rem 0.8rem;"><?= htmlspecialchars($work['category']) ?></span>
              </div>
            </div>

            <div class="p-8 -mt-10 relative z-20">
              <a href="<?= htmlspecialchars($work['link']) ?>" target="_blank">
                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-purple-300 transition-colors">
                  <?= htmlspecialchars($work['title']) ?>
                </h3>
              </a>
              <p class="text-slate-500 text-sm mb-6 line-clamp-2 group-hover:text-slate-300 transition-colors">
                <?= htmlspecialchars($work['description']) ?>
              </p>
              <div class="flex items-center justify-between">
                <button onclick='openModal(<?= json_encode($work) ?>)'
                        class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-purple-400 hover:text-white transition-all group/btn">
                  <span>Know More</span>
                  <div class="w-6 h-px bg-purple-400 group-hover/btn:w-10 transition-all"></div>
                </button>
                <a href="<?= htmlspecialchars($work['link']) ?>" target="_blank"
                   class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center hover:bg-white hover:text-black transition-all hover:scale-110">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-24 text-center" data-aos="zoom-in">
      <a href="Portfolio.php"
         class="inline-flex items-center gap-3 px-10 py-4 font-bold text-white bg-zinc-900/80 border border-white/10 rounded-full hover:bg-white hover:text-black transition-all duration-300 group backdrop-blur-xl">
        Browse Full Portfolio
        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>
    </div>
  </div>
</section>


<!-- Portfolio Modal -->
<div id="productModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 bg-black/92 backdrop-blur-md">
  <div class="bg-zinc-900/95 border border-zinc-800 max-w-2xl w-full rounded-3xl p-10 relative shadow-2xl">
    <button onclick="closeModal()" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center text-slate-400 hover:text-white transition-all">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
    <h2 id="modalTitle" class="text-3xl font-bold text-white mb-1"></h2>
    <p id="modalCategory" class="text-purple-400 font-bold uppercase tracking-widest text-[10px] mb-6"></p>
    <p id="modalAbout" class="text-slate-300 leading-relaxed text-base"></p>
    <div class="mt-8 pt-6 border-t border-zinc-800">
      <a id="modalLink" href="#" target="_blank"
         class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 px-6 py-3 rounded-xl font-bold text-sm transition-all">
        Visit Live Project ↗
      </a>
    </div>
  </div>
</div>
<script>
function openModal(work) {
  document.getElementById('modalTitle').innerText    = work.title;
  document.getElementById('modalCategory').innerText = work.category;
  document.getElementById('modalAbout').innerText    = work.about_product;
  document.getElementById('modalLink').href          = work.link;
  const modal = document.getElementById('productModal');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  document.body.style.overflow = 'hidden';
}
function closeModal() {
  const modal = document.getElementById('productModal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  document.body.style.overflow = 'auto';
}
</script>


<!-- ============================================================
     PARTNERS / CLIENTS SECTION
     ============================================================ -->
<section class="py-24 relative overflow-hidden" style="background:#05010a;border-top:1px solid rgba(255,255,255,0.04);">

  <div class="absolute inset-0 z-0 pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-600/15 blur-[100px] rounded-full animate-blob"></div>
    <div class="absolute top-1/2 right-1/4 w-80 h-80 bg-blue-600/8 blur-[120px] rounded-full animate-blob animation-delay-2000"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black opacity-70"></div>
  </div>

  <div class="section-container relative z-10 mb-12">
    <div class="text-center" data-aos="fade-up">
      <div class="badge mb-6 mx-auto"><span class="dot"></span>Collaborations</div>
      <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Trusted by Industry Leaders</h2>
      <div class="w-16 h-0.5 bg-gradient-to-r from-purple-600 to-transparent mx-auto mt-4 rounded-full"></div>
    </div>
  </div>

  <div class="logo-wall-wrapper group relative z-10 py-6">
    <div class="logo-wall-content">
      <div class="logo-set">
        <?php foreach ($clients as $client): ?>
          <a href="<?= htmlspecialchars($client['website_url']) ?>" target="_blank" class="partner-link group/item relative">
            <span class="absolute -top-12 left-1/2 bg-purple-600 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover/item:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap z-50 font-bold tracking-wider uppercase shadow-lg border border-white/10">
              <?= htmlspecialchars($client['client_name']) ?>
              <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-purple-600"></span>
            </span>
            <img src="<?= htmlspecialchars($client['client_logo_url']) ?>" alt="<?= htmlspecialchars($client['client_name']) ?>" class="partner-logo" />
          </a>
        <?php endforeach; ?>
      </div>
      <div class="logo-set" aria-hidden="true">
        <?php foreach ($clients as $client): ?>
          <a href="<?= htmlspecialchars($client['website_url']) ?>" target="_blank" class="partner-link group/item relative">
            <span class="absolute -top-12 left-1/2 bg-purple-600 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover/item:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap z-50 font-bold tracking-wider uppercase shadow-lg border border-white/10">
              <?= htmlspecialchars($client['client_name']) ?>
              <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-purple-600"></span>
            </span>
            <img src="<?= htmlspecialchars($client['client_logo_url']) ?>" alt="<?= htmlspecialchars($client['client_name']) ?>" class="partner-logo" />
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     CONTACT / CTA SECTION
     ============================================================ -->
<?php if (isset($_GET['status'])): $isSuccess = $_GET['status'] === 'success'; ?>
<div id="status-popup2" class="fixed top-10 right-10 z-[9999] animate-popup">
  <div class="relative overflow-hidden min-w-[300px] backdrop-blur-2xl bg-zinc-950/90 border <?= $isSuccess ? 'border-green-500/30' : 'border-red-500/30' ?> p-5 rounded-[2rem] shadow-2xl">
    <div class="relative flex items-center gap-4">
      <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?= $isSuccess ? 'bg-green-500' : 'bg-red-500' ?> text-white font-bold">
        <?= $isSuccess ? '✓' : '!' ?>
      </div>
      <div>
        <h5 class="text-white font-black uppercase tracking-widest text-[9px]"><?= $isSuccess ? 'Success' : 'Error' ?></h5>
        <p class="text-slate-300 text-xs"><?= $isSuccess ? 'Message received.' : 'Please try again.' ?></p>
      </div>
    </div>
    <div class="absolute bottom-0 left-0 h-1 <?= $isSuccess ? 'bg-green-500' : 'bg-red-500' ?> animate-progress"></div>
  </div>
</div>
<?php endif; ?>

<section class="py-24 relative overflow-hidden" style="background:#0A0118;">
  <div class="absolute inset-0 z-0 pointer-events-none">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-purple-600/8 blur-[120px] rounded-full animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-900/8 blur-[120px] rounded-full animate-blob animation-delay-2000"></div>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 relative z-10">
    <div class="grid lg:grid-cols-12 gap-10 items-start">

      <!-- Left Info -->
      <div class="lg:col-span-3 lg:sticky lg:top-24 space-y-8 contact-anim">
        <div>
          <div class="badge mb-6"><span class="dot"></span>Contact</div>
          <div class="reveal-line mb-5"></div>
          <h2 class="text-white mb-4" style="font-size:clamp(2rem,4vw,3rem);font-weight:900;line-height:1;letter-spacing:-0.03em;">
            Let's Build<br><span class="gradient-text">Legendary.</span>
          </h2>
          <p class="text-slate-400 text-sm leading-relaxed">I help visionary brands turn complex ideas into high-performance digital products.</p>
        </div>

        <div class="space-y-3">
          <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 contact-anim">
            <span class="text-purple-500 mt-0.5">📍</span>
            <div>
              <p class="text-white text-[11px] font-bold uppercase tracking-wider">Location</p>
              <p class="text-slate-400 text-xs mt-0.5">Ijebu-Ode, Ogun State, Nigeria</p>
            </div>
          </div>
          <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 contact-anim">
            <span class="text-purple-500 mt-0.5">📧</span>
            <div>
              <p class="text-white text-[11px] font-bold uppercase tracking-wider">Email</p>
              <p class="text-slate-400 text-xs mt-0.5">support@seyimultiservice.com.ng</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="lg:col-span-6 contact-anim">
        <div class="bg-white/[0.02] backdrop-blur-3xl p-8 rounded-[2.5rem] shadow-2xl border border-white/[0.05]">
          <h4 class="text-xl font-bold text-white mb-1">Project Brief</h4>
          <p class="text-slate-500 text-sm mb-8">Provide details to get an accurate quote.</p>

          <form action="includes/submit_logic.php" method="POST" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div class="relative">
                <input type="text" name="full_name" required placeholder=" "
                  class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-colors" />
                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold tracking-wider transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-[10px] peer-focus:text-purple-500">Full Name</label>
              </div>
              <div class="relative">
                <select name="budget" required
                  class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 cursor-pointer transition-colors">
                  <option value="" disabled selected class="bg-[#0A0118]">Budget Range</option>
                  <option value="Below" class="bg-[#0A0118]">Below $500</option>
                  <option value="500"   class="bg-[#0A0118]">$500 – $1,000</option>
                  <option value="1000"  class="bg-[#0A0118]">$1,000 – $5,000</option>
                  <option value="5000"  class="bg-[#0A0118]">$5,000+</option>
                </select>
                <label class="absolute left-0 -top-3.5 text-purple-500 text-[10px] uppercase font-bold tracking-wider">Budget</label>
              </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
              <div class="relative">
                <input type="email" name="email" required placeholder=" "
                  class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-colors" />
                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold tracking-wider transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-[10px] peer-focus:text-purple-500">Email Address</label>
              </div>
              <div class="relative">
                <input type="tel" name="phone" required placeholder=" "
                  class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-colors" />
                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold tracking-wider transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-[10px] peer-focus:text-purple-500">Phone</label>
              </div>
            </div>
            <div class="relative">
              <input type="text" name="project_purpose" required placeholder=" "
                class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-colors" />
              <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Project Purpose</label>
            </div>
            <div class="relative">
              <textarea name="project_description" required placeholder=" "
                class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none h-20 resize-none"></textarea>
              <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Description</label>
            </div>
            <button type="submit" name="submit_inquiry"
              class="btn-primary w-full justify-center py-4 rounded-xl text-[11px] tracking-widest uppercase font-black">
              Launch Project Inquiry
            </button>
          </form>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="lg:col-span-3 space-y-6">
        <div class="bg-white/[0.02] border border-white/5 p-6 rounded-[2rem]">
          <div class="flex justify-between items-center mb-4">
            <h5 class="text-white text-[10px] font-black uppercase tracking-widest">Availability</h5>
            <span class="text-green-500 text-[8px] animate-pulse font-bold">● LIVE</span>
          </div>
          <div class="text-white text-center mb-2 font-bold text-xs">
            <?php echo date('F Y'); ?>
          </div>
          <div class="grid grid-cols-7 gap-1">
            <?php
            $days = ["S","M","T","W","T","F","S"];
            foreach ($days as $d) echo "<div class='text-[8px] text-zinc-600 text-center font-bold'>$d</div>";
            $daysInMonth = date('t');
            $today = date('j');
            for ($i = 1; $i <= $daysInMonth; $i++) {
              $active = ($i == $today) ? 'active' : '';
              echo "<div class='calendar-day $active'>$i</div>";
            }
            ?>
          </div>
          <p class="text-[9px] text-zinc-600 mt-4 text-center italic">Currently accepting new projects.</p>
        </div>

        <div class="p-6 rounded-[2rem] bg-gradient-to-br from-purple-600/10 to-transparent border border-purple-500/15">
          <h5 class="text-white text-[10px] font-black uppercase tracking-widest mb-4">Quick Chat</h5>
          <a href="https://wa.link/me3ipi" target="_blank" class="flex items-center justify-between group py-2 border-b border-white/5">
            <span class="text-slate-300 text-sm group-hover:text-white transition-colors">WhatsApp Business</span>
            <span class="text-purple-400 group-hover:translate-x-1 transition-transform inline-block">→</span>
          </a>
          <a href="https://www.linkedin.com/in/kazeem-kabiru-477abbb6/" target="_blank" class="flex items-center justify-between group py-2 mt-1">
            <span class="text-slate-300 text-sm group-hover:text-white transition-colors">LinkedIn Profile</span>
            <span class="text-purple-400 group-hover:translate-x-1 transition-transform inline-block">→</span>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


<?php require_once __DIR__ . '/includes/footer.php'; ?>


<!-- ============================================================
     THREE.JS PARTICLE NETWORK  +  ALL JS INIT
     ============================================================ -->
<script>
/* Canvas init runs after defer scripts (Three.js) are ready */
function initHeroCanvas() {
  var canvas = document.getElementById('heroCanvas');
  if (!canvas || typeof THREE === 'undefined') return;

  var W = function() { return window.innerWidth; };
  var H = function() { return window.innerHeight; };

  var scene    = new THREE.Scene();
  var camera   = new THREE.PerspectiveCamera(55, W() / H(), 0.1, 100);
  camera.position.set(0, 0, 10);

  var renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
  renderer.setSize(W(), H());
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  /* --- Particles --- */
  var COUNT = 160;
  var pPos  = new Float32Array(COUNT * 3);
  var pVel  = [];

  for (var i = 0; i < COUNT; i++) {
    pPos[i*3+0] = (Math.random() - 0.5) * 32;
    pPos[i*3+1] = (Math.random() - 0.5) * 22;
    pPos[i*3+2] = (Math.random() - 0.5) * 8;
    pVel.push({ x: (Math.random() - 0.5) * 0.007, y: (Math.random() - 0.5) * 0.007 });
  }

  var pGeo = new THREE.BufferGeometry();
  pGeo.setAttribute('position', new THREE.BufferAttribute(pPos, 3));

  var pMat = new THREE.PointsMaterial({
    size: 0.07, color: 0xa855f7,
    transparent: true, opacity: 0.75,
    blending: THREE.AdditiveBlending, depthWrite: false
  });
  scene.add(new THREE.Points(pGeo, pMat));

  /* --- Connection lines --- */
  var MAX_LINES = 300;
  var lPos = new Float32Array(MAX_LINES * 6);
  var lGeo = new THREE.BufferGeometry();
  lGeo.setAttribute('position', new THREE.BufferAttribute(lPos, 3));
  lGeo.setDrawRange(0, 0);

  var lMat = new THREE.LineBasicMaterial({
    color: 0x7c3aed, transparent: true, opacity: 0.22,
    blending: THREE.AdditiveBlending, depthWrite: false
  });
  scene.add(new THREE.LineSegments(lGeo, lMat));

  /* --- Larger glow spheres --- */
  [[-5, 3, -3], [6, -2, -5], [0, -5, -2]].forEach(function(pos) {
    var g = new THREE.SphereGeometry(0.25, 8, 8);
    var m = new THREE.MeshBasicMaterial({ color: 0xa855f7, transparent: true, opacity: 0.3, blending: THREE.AdditiveBlending });
    var mesh = new THREE.Mesh(g, m);
    mesh.position.set(pos[0], pos[1], pos[2]);
    scene.add(mesh);
  });

  var mouse = { x: 0, y: 0 };
  var camX  = 0, camY = 0;
  var DIST  = 4.5;
  var frame = 0;

  document.addEventListener('mousemove', function(e) {
    mouse.x = (e.clientX / W() - 0.5) * 2;
    mouse.y = -(e.clientY / H() - 0.5) * 2;
  });

  function animate() {
    requestAnimationFrame(animate);
    frame++;

    /* update particles */
    for (var i = 0; i < COUNT; i++) {
      pPos[i*3+0] += pVel[i].x;
      pPos[i*3+1] += pVel[i].y;
      if (Math.abs(pPos[i*3+0]) > 16) pVel[i].x *= -1;
      if (Math.abs(pPos[i*3+1]) > 11) pVel[i].y *= -1;
    }
    pGeo.attributes.position.needsUpdate = true;

    /* update connections every 2 frames */
    if (frame % 2 === 0) {
      var lc = 0;
      for (var i = 0; i < COUNT && lc < MAX_LINES; i++) {
        for (var j = i + 1; j < COUNT && lc < MAX_LINES; j++) {
          var dx = pPos[i*3]-pPos[j*3], dy = pPos[i*3+1]-pPos[j*3+1], dz = pPos[i*3+2]-pPos[j*3+2];
          if (Math.sqrt(dx*dx + dy*dy + dz*dz) < DIST) {
            lPos[lc*6+0]=pPos[i*3];   lPos[lc*6+1]=pPos[i*3+1]; lPos[lc*6+2]=pPos[i*3+2];
            lPos[lc*6+3]=pPos[j*3];   lPos[lc*6+4]=pPos[j*3+1]; lPos[lc*6+5]=pPos[j*3+2];
            lc++;
          }
        }
      }
      lGeo.attributes.position.needsUpdate = true;
      lGeo.setDrawRange(0, lc * 2);
    }

    /* camera parallax */
    camX += (mouse.x * 1.8 - camX) * 0.025;
    camY += (mouse.y * 1.2 - camY) * 0.025;
    camera.position.x = camX;
    camera.position.y = camY;
    camera.lookAt(0, 0, 0);

    renderer.render(scene, camera);
  }

  animate();

  window.addEventListener('resize', function() {
    camera.aspect = W() / H();
    camera.updateProjectionMatrix();
    renderer.setSize(W(), H());
  });
}
</script>


<!-- ============================================================
     ANIMATIONS — GSAP + AOS + VANILLA TILT + TYPEWRITER
     ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  var isMobile = window.matchMedia('(max-width:768px)').matches;
  var isTouch  = ('ontouchstart' in window);

  /* ─── Three.js canvas ─── */
  initHeroCanvas();

  /* ─── AOS (scroll reveal library) ─── */
  if (typeof AOS !== 'undefined') {
    AOS.init({
      once:     true,
      duration: isMobile ? 650 : 900,
      offset:   isMobile ? 30  : 80,
      easing:   'ease-out-cubic',
      mirror:   false,
      anchorPlacement: 'top-bottom'
    });
  }

  /* ─── GSAP + ScrollTrigger ─── */
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    /* ── 1. Section heading reveal lines ── */
    gsap.utils.toArray('.reveal-line').forEach(function(line) {
      gsap.from(line, {
        scrollTrigger: { trigger: line, start: 'top 90%', toggleActions: 'play none none none' },
        scaleX: 0, transformOrigin: 'left', duration: 0.8, ease: 'power3.out'
      });
    });

    /* ── 2. Clip-reveal headings (large display text) ── */
    gsap.utils.toArray('.clip-reveal').forEach(function(wrap) {
      var inner = wrap.querySelector('.clip-reveal-inner');
      if (!inner) return;
      gsap.from(inner, {
        scrollTrigger: { trigger: wrap, start: 'top 88%', toggleActions: 'play none none none' },
        y: '110%', skewY: 4, duration: 0.9, ease: 'power4.out'
      });
    });

    /* ── 3. Service cards — stagger in with scale ── */
    var sCards = gsap.utils.toArray('.service-card');
    if (sCards.length) {
      gsap.from(sCards, {
        scrollTrigger: { trigger: sCards[0], start: 'top 85%', toggleActions: 'play none none none' },
        y: 60, opacity: 0, scale: 0.94,
        duration: 0.75, stagger: 0.1, ease: 'power3.out'
      });
    }

    /* ── 4. Process cards — stagger left-to-right ── */
    var pCards = gsap.utils.toArray('.process-card');
    if (pCards.length) {
      gsap.from(pCards, {
        scrollTrigger: { trigger: pCards[0], start: 'top 88%', toggleActions: 'play none none none' },
        x: 40, opacity: 0, duration: 0.7, stagger: 0.13, ease: 'power3.out'
      });
    }

    /* ── 5. Portfolio cards — fan-in ── */
    var portCards = gsap.utils.toArray('#recent-portfolio .group');
    if (portCards.length) {
      gsap.from(portCards, {
        scrollTrigger: { trigger: portCards[0], start: 'top 85%', toggleActions: 'play none none none' },
        y: 70, opacity: 0, scale: 0.92,
        duration: 0.8, stagger: 0.12, ease: 'power3.out'
      });
    }

    /* ── 6. Animated stat counters (hero) ── */
    document.querySelectorAll('.counter').forEach(function(el) {
      var target = parseInt(el.dataset.target, 10);
      var triggered = false;
      ScrollTrigger.create({
        trigger: el,
        start: 'top 90%',
        once: true,
        onEnter: function() {
          if (triggered) return;
          triggered = true;
          var obj = { val: 0 };
          gsap.to(obj, {
            val: target, duration: 2.4, ease: 'power2.out',
            onUpdate: function() {
              el.textContent = Math.round(obj.val);
            },
            onComplete: function() {
              /* pop effect on parent */
              var parent = el.closest('.text-5xl, .text-6xl');
              if (parent) { parent.classList.add('stat-animated'); }
            }
          });
        }
      });
    });

    /* ── 7. Section background parallax (desktop only) ── */
    if (!isMobile) {
      gsap.utils.toArray('.parallax-bg').forEach(function(el) {
        gsap.to(el, {
          scrollTrigger: { trigger: el.parentElement, scrub: 1.5 },
          y: -60, ease: 'none'
        });
      });

      /* Subtle rotation on hero glow orbs */
      gsap.utils.toArray('.animate-blob').forEach(function(orb, i) {
        gsap.to(orb, {
          scrollTrigger: { trigger: document.body, scrub: 2 },
          y: (i % 2 === 0 ? -80 : 80), ease: 'none'
        });
      });
    }

    /* ── 8. Partners logo section reveal ── */
    var partnersSection = document.querySelector('.logo-wall-wrapper');
    if (partnersSection) {
      gsap.from(partnersSection, {
        scrollTrigger: { trigger: partnersSection, start: 'top 90%', toggleActions: 'play none none none' },
        opacity: 0, y: 30, duration: 0.9, ease: 'power3.out'
      });
    }

    /* ── 9. Contact section elements stagger ── */
    var contactItems = document.querySelectorAll('.contact-anim');
    if (contactItems.length) {
      gsap.from(contactItems, {
        scrollTrigger: { trigger: contactItems[0], start: 'top 88%', toggleActions: 'play none none none' },
        y: 40, opacity: 0, duration: 0.7, stagger: 0.1, ease: 'power3.out'
      });
    }

    /* ── 10. Floating hero cards — extra bounce on load ── */
    gsap.utils.toArray('.animate-float, .animate-float-2').forEach(function(card, i) {
      gsap.from(card, {
        opacity: 0, x: 30, scale: 0.9,
        duration: 0.9, delay: 1.2 + i * 0.2, ease: 'back.out(1.5)'
      });
    });

    /* ── 11. Scroll-triggered border glow on section headings ── */
    gsap.utils.toArray('.section-glow-trigger').forEach(function(el) {
      ScrollTrigger.create({
        trigger: el,
        start: 'top 80%',
        onEnter: function() { el.classList.add('animate-border-glow'); }
      });
    });

    /* ── 12. Nav links typed-on effect on page load ── */
    gsap.from('#main-nav', { y: -80, opacity: 0, duration: 0.8, delay: 0.3, ease: 'power3.out' });
  }

  /* ─── VanillaTilt (desktop / non-touch only) ─── */
  if (!isTouch && typeof VanillaTilt !== 'undefined') {
    VanillaTilt.init(document.querySelectorAll('[data-tilt]'), {
      max: 7, speed: 400, glare: true, 'max-glare': 0.12, scale: 1.02
    });
  }

  /* ─── Magnetic buttons (desktop only) ─── */
  if (!isTouch) {
    document.querySelectorAll('.magnetic').forEach(function(btn) {
      btn.addEventListener('mousemove', function(e) {
        var r = btn.getBoundingClientRect();
        var x = (e.clientX - r.left - r.width  / 2) * 0.26;
        var y = (e.clientY - r.top  - r.height / 2) * 0.26;
        btn.style.transform = 'translate(' + x + 'px,' + y + 'px)';
      });
      btn.addEventListener('mouseleave', function() {
        btn.style.transition = 'transform 0.55s cubic-bezier(0.23,1,0.32,1)';
        btn.style.transform  = 'translate(0,0)';
        setTimeout(function() { btn.style.transition = ''; }, 550);
      });
      btn.addEventListener('mouseenter', function() {
        btn.style.transition = 'transform 0.1s ease';
      });
    });
  }

  /* ─── Typewriter effect ─── */
  var roles  = ["'Frontend'", "'FullStack'", "'Plugins'", "'Themes'", "'Backend'"];
  var rIdx   = 0, cIdx = 0, del = false;
  var twEl   = document.getElementById('typewriter');
  function typeWriter() {
    if (!twEl) return;
    var cur = roles[rIdx];
    twEl.textContent = del ? cur.substring(0, cIdx--) : cur.substring(0, cIdx++);
    var spd = del ? 75 : 140;
    if (!del && cIdx === cur.length + 1) { del = true; spd = 1800; }
    else if (del && cIdx === 0)          { del = false; rIdx = (rIdx + 1) % roles.length; spd = 380; }
    setTimeout(typeWriter, spd);
  }
  typeWriter();

  /* ─── Touch swipe to close mobile menu ─── */
  if (isTouch) {
    var menu      = document.getElementById('mobile-menu');
    var touchStartX = 0;
    menu.addEventListener('touchstart', function(e) { touchStartX = e.changedTouches[0].screenX; }, { passive:true });
    menu.addEventListener('touchend',   function(e) {
      var dx = e.changedTouches[0].screenX - touchStartX;
      if (dx > 60) { /* swipe right → close */
        menu.style.transform     = 'translateX(100%)';
        menu.style.pointerEvents = 'none';
        document.body.style.overflow = '';
      }
    }, { passive:true });
  }

  /* ─── Intersection observer fallback for browsers without GSAP ─── */
  if (typeof gsap === 'undefined' && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) {
          e.target.classList.add('anim-fade-up');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.service-card, .process-card').forEach(function(el) { io.observe(el); });
  }

});
</script>
