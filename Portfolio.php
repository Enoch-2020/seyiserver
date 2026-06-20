<?php 
require_once __DIR__ . '/includes/db.php'; 
require_once __DIR__ . '/includes/header.php'; 

// 1. Fetch unique categories for the dropdown
$cat_stmt = $pdo->query("SELECT DISTINCT category FROM Services WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// 2. Handle Filtering Logic
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';

if ($selected_category && $selected_category !== 'All') {
    $stmt = $pdo->prepare("SELECT * FROM Services WHERE category = ? ORDER BY id DESC");
    $stmt->execute([$selected_category]);
} else {
    $stmt = $pdo->query("SELECT * FROM Services ORDER BY id DESC");
}
$all_projects = $stmt->fetchAll();
?>

<main class="bg-black min-h-screen pt-32 pb-20 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-purple-900/10 blur-[120px] rounded-full"></div>

    <div class="section-container relative z-10">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
            <div data-aos="fade-right">
                <h1 class="text-6xl font-black tracking-tighter text-white mb-4">
                    Our <span class="text-purple-500">Works.</span>
                </h1>
                <p class="text-slate-400 text-lg">Latest digital results from the SeyiDev lab.</p>
            </div>

            <div class="relative min-w-[250px]" data-aos="fade-left">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Filter Projects</label>
                <div class="relative">
                    <select 
                        onchange="location = this.value;" 
                        class="w-full bg-transparent text-slate-300 border border-zinc-800 px-6 py-4 rounded-2xl appearance-none focus:border-purple-500 outline-none cursor-pointer transition-all hover:border-zinc-700 shadow-2xl"
                    >
                        <option value="portfolio.php?category=All" class="bg-zinc-950" <?= $selected_category == 'All' ? 'selected' : '' ?>>All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="portfolio.php?category=<?= urlencode($cat) ?>" <?= $selected_category == $cat ? 'selected' : '' ?> class="bg-zinc-950">
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php if (count($all_projects) > 0): ?>
                <?php foreach ($all_projects as $index => $project): ?>
                    <div class="group relative" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                        <div class="relative bg-zinc-900/30 backdrop-blur-sm rounded-3xl overflow-hidden border border-zinc-800/50 h-full flex flex-col hover:border-purple-500/50 transition-all duration-500">
                            
                            <div class="relative h-60 overflow-hidden bg-zinc-800">
                                <img src="<?= htmlspecialchars($project['feature_image']) ?>" 
                                     alt="<?= htmlspecialchars($project['title']) ?>"
                                     class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110"
                                     onerror="this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-60"></div>
                            </div>

                            <div class="p-8 flex-grow">
                                <span class="text-purple-500 font-mono text-[10px] uppercase tracking-widest block mb-3"><?= htmlspecialchars($project['category']) ?></span>
                                <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank">
                                    <h3 class="text-2xl font-bold text-white mb-4 hover:text-purple-400 transition"><?= htmlspecialchars($project['title']) ?></h3>
                                </a>
                                <p class="text-slate-400 text-sm line-clamp-3"><?= htmlspecialchars($project['description']) ?></p>
                            </div>
                            
                            <div class="p-8 pt-0">
                                <button onclick='openModal(<?= json_encode($project, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                        class="w-full py-4 bg-zinc-800/50 hover:bg-purple-600 text-white transition-all rounded-xl font-bold border border-white/5 shadow-lg">
                                    KNOW MORE
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center border border-dashed border-zinc-800 rounded-3xl">
                    <p class="text-slate-500 italic text-lg">No projects found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<div id="productModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeModal()"></div>
    
    <div class="relative bg-zinc-900 border border-zinc-800 max-w-2xl w-full rounded-[2.5rem] p-8 md:p-12 shadow-2xl transform transition-transform duration-300 scale-95" id="modalContent">
        <button onclick="closeModal()" class="absolute top-8 right-8 text-slate-500 hover:text-white transition-colors z-10">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        
        <span id="modalCategory" class="text-purple-500 font-mono text-xs tracking-[0.3em] uppercase block mb-4"></span>
        <h2 id="modalTitle" class="text-4xl font-black text-white mb-6 leading-tight"></h2>
        
        <div class="prose prose-invert max-h-[40vh] overflow-y-auto pr-4 mb-10 custom-scrollbar">
            <p id="modalAbout" class="text-slate-300 text-lg leading-relaxed"></p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <a id="modalLink" href="#" target="_blank" class="bg-purple-600 hover:bg-purple-500 text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-purple-600/20">Visit Live Project</a>
            <button onclick="closeModal()" class="px-8 py-4 border border-zinc-700 text-slate-300 rounded-xl font-bold hover:bg-zinc-800 transition-all">Close</button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>

<script>
function openModal(project) {
    const modal = document.getElementById('productModal');
    const content = document.getElementById('modalContent');
    
    // Fill Data
    document.getElementById('modalTitle').innerText = project.title;
    document.getElementById('modalCategory').innerText = project.category;
    document.getElementById('modalAbout').innerText = project.about_product || "Our team is currently finalizing the detailed case study for this project. Stay tuned!";
    document.getElementById('modalLink').href = project.link;
    
    // Show Modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('productModal');
    const content = document.getElementById('modalContent');
    
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>