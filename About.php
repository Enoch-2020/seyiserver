<?php 
require_once __DIR__ . '/includes/db.php'; 
if (!isset($pdo) && isset($db)) { $pdo = $db; }

require_once __DIR__ . '/includes/header.php'; 

try {
    // 1. Fetch team members
    $team_stmt = $pdo->query("SELECT * FROM team_members ORDER BY id ASC");
    $team_members = $team_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Fetch social links and group them by member_id
    $social_stmt = $pdo->query("SELECT * FROM member_social_links");
    $all_socials = $social_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $socials_by_member = [];
    foreach ($all_socials as $link) {
        $socials_by_member[$link['member_id']][] = $link;
    }

    $journey_stmt = $pdo->query("SELECT * FROM journey ORDER BY year ASC");
    $journey_items = $journey_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $team_members = []; $journey_items = []; $socials_by_member = [];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root { --accent: #7c3aed; }

    .about-hero {
        width: 100%;
        padding-top: 120px; 
        padding-bottom: 60px;
        min-height: 35vh; 
        background: linear-gradient(to bottom, rgba(5, 1, 10, 0.8), rgba(5, 1, 10, 1)), 
                    url('assets/about.jpg'); 
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 5px; 
        max-width: 1100px;
        margin: 0 auto;
    }

    .team-card { padding: 20px 10px; transition: 0.3s ease; }

    .team-circle {
        width: 180px;
        height: 180px;
        border-radius: 20%; 
        overflow: hidden;
        border: 1px solid rgba(124, 58, 237, 0.1);
        margin: 0 auto 1.25rem;
        transition: 0.4s ease;
    }

    .team-card:hover .team-circle {
        border-color: var(--accent);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(124, 58, 237, 0.2);
    }

    .team-img { width: 100%; height: 100%; object-fit: cover; }

    /* Social Links Styling */
    .social-row {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 12px;
        min-height: 20px;
    }

    .social-icon-link {
        color: #71717a; /* zinc-400 */
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-icon-link:hover {
        color: var(--accent);
        transform: translateY(-2px);
    }

    @media (max-width: 1024px) { .team-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .team-grid { grid-template-columns: repeat(1, 1fr); } }

    .journey-hidden { display: none; }
</style>

<section class="about-hero">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-4">
            About <span class="text-purple-500">Us.</span>
        </h1>
        <p class="text-zinc-400 text-xs md:text-sm max-w-2xl mx-auto uppercase tracking-[0.3em] font-bold">
            Innovation Driven by Precision
        </p>
    </div>
</section>

<section class="py-24 bg-[#05010a]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-purple-500 text-[10px] font-black uppercase tracking-[0.4em] block mb-2">Expertise</span>
            <h2 class="text-3xl font-black text-white uppercase tracking-tight">The Collective</h2>
        </div>

        <div class="team-grid">
            <?php foreach($team_members as $m): 
                $member_id = $m['id'];
                $socials = $socials_by_member[$member_id] ?? [];
            ?>
                <div class="team-card text-center" data-aos="fade-up">
                    <div class="team-circle">
                        <img src="<?= htmlspecialchars($m['picture_url']) ?>" class="team-img" alt="Member">
                    </div>
                    
                    <h4 class="text-white font-bold text-sm tracking-tight mb-1"><?= htmlspecialchars($m['name']) ?></h4>
                    <p class="text-zinc-500 text-[9px] uppercase font-black tracking-widest"><?= htmlspecialchars($m['designation']) ?></p>
                    
                    <div class="social-row">
                        <?php foreach($socials as $s): 
                            $platform = strtolower($s['platform_name']);
                            $icon = 'fa-solid fa-link'; // Default icon
                            
                            // Check for specific platforms
                            if (str_contains($platform, 'linkedin')) $icon = 'fab fa-linkedin-in';
                            elseif (str_contains($platform, 'twitter') || str_contains($platform, 'x')) $icon = 'fab fa-x-twitter';
                            elseif (str_contains($platform, 'github')) $icon = 'fab fa-github';
                            elseif (str_contains($platform, 'instagram')) $icon = 'fab fa-instagram';
                            elseif (str_contains($platform, 'behance')) $icon = 'fab fa-behance';
                            elseif (str_contains($platform, 'dribbble')) $icon = 'fab fa-dribbble';
                        ?>
                            <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" class="social-icon-link" title="<?= htmlspecialchars($s['platform_name']) ?>">
                                <i class="<?= $icon ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-24 bg-[#080212] border-t border-zinc-900">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-black text-white uppercase mb-16">Our Journey</h2>
        <div class="max-w-4xl mx-auto px-6 relative text-left">
            <div class="absolute left-1/2 -translate-x-1/2 h-full w-[1px] bg-zinc-800"></div>
            <div id="journey-list">
                <?php foreach($journey_items as $index => $row): 
                    $isHidden = ($index >= 4);
                ?>
                    <div class="journey-item relative mb-16 <?= $isHidden ? 'journey-hidden' : '' ?>" data-aos="fade-up">
                        <div class="absolute left-1/2 -translate-x-1/2 w-3 h-3 bg-purple-600 rounded-full shadow-[0_0_15px_rgba(124,58,237,1)] z-10"></div>
                        <div class="w-1/2 <?= ($index % 2 == 0) ? 'pr-12 text-right' : 'ml-auto pl-12 text-left' ?>">
                            <span class="text-2xl font-black text-white/10 block leading-none"><?= htmlspecialchars($row['year']) ?></span>
                            <h5 class="text-purple-500 font-bold text-xs uppercase mb-2 tracking-wide"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="text-zinc-500 text-xs leading-relaxed"><?= htmlspecialchars($row['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if(count($journey_items) > 4): ?>
            <div class="text-center mt-12 relative z-20">
                <button id="showMoreBtn" class="px-10 py-4 bg-white/5 border border-white/10 text-white font-black text-[9px] uppercase tracking-[0.2em] rounded-full hover:bg-purple-600 transition-all">
                    View More History
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    document.getElementById('showMoreBtn')?.addEventListener('click', function() {
        document.querySelectorAll('.journey-hidden').forEach(item => {
            item.classList.remove('journey-hidden');
            if(window.AOS) AOS.refresh(); 
        });
        this.parentElement.style.display = 'none';
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>