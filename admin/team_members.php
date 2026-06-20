<?php 
require_once __DIR__ . '/sidebar.php'; 
require_once __DIR__ . '/../includes/header.php'; 

if (!isset($pdo) && isset($db)) { $pdo = $db; }

$message = "";

// 2. LOGIC FOR INDIVIDUAL & BULK DELETION
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM team_members WHERE id = ?")->execute([$delete_id]);
    $message = "<div class='text-[9px] bg-amber-500/10 border border-amber-500/20 text-amber-500 p-3 rounded-xl mb-6 uppercase tracking-[0.2em] font-bold'>⚠️ Member removed.</div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_delete'])) {
    if (!empty($_POST['selected_members'])) {
        $placeholders = implode(',', array_fill(0, count($_POST['selected_members']), '?'));
        $stmt = $pdo->prepare("DELETE FROM team_members WHERE id IN ($placeholders)");
        $stmt->execute($_POST['selected_members']);
        $message = "<div class='text-[9px] bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl mb-6 uppercase tracking-[0.2em] font-bold'>🗑️ Bulk deletion complete.</div>";
    }
}

// 3. LOGIC FOR TEAM MEMBER ONBOARDING
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    try {
        $pdo->beginTransaction();
        $target_dir = "../assets/img/team/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = time() . '_' . basename($_FILES["picture"]["name"]);
        $target_file = $target_dir . $file_name;
        $db_path = "assets/img/team/" . $file_name;

        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO team_members (name, designation, picture_url) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['designation'], $db_path]);
            $member_id = $pdo->lastInsertId();

            if (!empty($_POST['platforms'])) {
                $social_stmt = $pdo->prepare("INSERT INTO member_social_links (member_id, platform_name, url) VALUES (?, ?, ?)");
                foreach ($_POST['platforms'] as $index => $platform) {
                    $url = $_POST['urls'][$index];
                    if (!empty($platform) && !empty($url)) {
                        $social_stmt->execute([$member_id, $platform, $url]);
                    }
                }
            }
            $pdo->commit();
            $message = "<div class='text-[9px] bg-green-500/10 border border-green-500/20 text-green-500 p-3 rounded-xl mb-6 uppercase tracking-[0.2em] font-bold'>🚀 Innovator deployed.</div>";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='text-[9px] bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-xl mb-6 uppercase tracking-[0.2em] font-bold'>❌ Error: " . $e->getMessage() . "</div>";
    }
}

$members = $pdo->query("SELECT * FROM team_members ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="admin-main-content pt-28 pb-12 px-8">
    <div class="max-w-5xl mx-auto">
        
        <?= $message; ?>

        <form action="" method="POST" onsubmit="return confirm('Confirm bulk deletion?');">
            <section class="mb-20">
                <div class="flex items-end justify-between mb-8 border-b border-zinc-900 pb-3">
                    <div>
                        <h1 class="text-2xl font-black text-white mb-1 uppercase tracking-tighter">The <span class="text-purple-500">Collective.</span></h1>
                        <p class="text-zinc-600 font-bold uppercase text-[8px] tracking-[0.4em]">Management of Experts</p>
                    </div>
                    <button type="submit" name="bulk_delete" class="text-[8px] font-black uppercase tracking-widest text-zinc-500 hover:text-red-500 transition-colors mb-1">
                        <i class="fa-solid fa-trash-can mr-1"></i> Delete Selected
                    </button>
                </div>

                <div class="flex flex-wrap -mx-2">
                    <?php foreach($members as $m): ?>
                        <div class="w-full md:w-1/2 px-2 mb-4">
                            <div class="group flex items-center justify-between bg-zinc-900/20 border border-zinc-800/40 p-3 rounded-2xl hover:border-zinc-700 transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="selected_members[]" value="<?= $m['id'] ?>" class="accent-purple-600 w-3 h-3 bg-zinc-950 border-zinc-800 rounded">
                                    <img src="../<?= htmlspecialchars($m['picture_url']) ?>" class="w-10 h-10 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all duration-500 border border-zinc-800">
                                    <div>
                                        <h3 class="text-zinc-200 font-bold text-[10px] uppercase tracking-tight"><?= htmlspecialchars($m['name']) ?></h3>
                                        <p class="text-purple-500 font-medium text-[8px] uppercase tracking-widest mt-0.5"><?= htmlspecialchars($m['designation']) ?></p>
                                    </div>
                                </div>
                                
                                <a href="?delete=<?= $m['id'] ?>" onclick="return confirm('Remove this member?')" 
                                   class="mr-3 text-zinc-800 hover:text-red-500 transition-all text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </form>

        <section>
            <div class="mb-8 border-b border-zinc-900 pb-3">
                <h1 class="text-2xl font-black text-white mb-1 uppercase tracking-tighter">New <span class="text-blue-500">Member.</span></h1>
                <p class="text-zinc-600 font-bold uppercase text-[8px] tracking-[0.4em]">Expansion Protocol</p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="mt-8">
                <div class="grid lg:grid-cols-12 gap-10">
                    
                    <div class="lg:col-span-7 space-y-6">
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.2em] ml-1">Full Identity</label>
                                <input type="text" name="name" required class="w-full bg-zinc-950/40 border border-zinc-800 p-3 rounded-xl text-[11px] text-white focus:border-purple-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.2em] ml-1">Operation Role</label>
                                <input type="text" name="designation" required placeholder="e.g. UX Architect" 
                                       class="w-full bg-zinc-950/40 border border-zinc-800 p-3 rounded-xl text-[11px] text-white focus:border-purple-500 outline-none">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.2em] ml-1">Portrait Source</label>
                            <input type="file" name="picture" accept="image/*" required 
                                   class="w-full bg-zinc-950/40 border border-zinc-800 p-2.5 rounded-xl text-[9px] text-zinc-500 file:bg-zinc-800 file:text-white file:rounded-lg file:border-0 file:px-3 file:py-1 file:mr-3 file:font-black file:text-[8px] file:uppercase cursor-pointer hover:border-zinc-700 transition-all">
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="bg-zinc-950/20 border border-zinc-900/50 p-5 rounded-3xl space-y-4">
                            <div class="flex items-center justify-between border-b border-zinc-900 pb-2">
                                <h4 class="text-white text-[8px] font-black uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-share-nodes text-blue-500"></i> Social Profiles
                                </h4>
                                <button type="button" onclick="addSocialField()" class="text-blue-500 hover:text-white text-[8px] font-black uppercase tracking-widest transition-colors">Add Field +</button>
                            </div>
                            
                            <div id="social-container" class="space-y-3 max-h-[220px] overflow-y-auto custom-scrollbar pr-1">
                                <div class="social-group relative bg-zinc-900/40 p-4 rounded-2xl border border-zinc-800/50 group/social">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-zinc-800 text-zinc-400 rounded-full text-[10px] flex items-center justify-center hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover/social:opacity-100">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    
                                    <select name="platforms[]" class="w-full bg-transparent text-[8px] font-bold uppercase text-zinc-300 focus:outline-none border-b border-zinc-800 pb-2 mb-3 appearance-none">
                                        <option value="" class="bg-zinc-950">Select Platform</option>
                                        <option value="LinkedIn" class="bg-zinc-950">LinkedIn</option>
                                        <option value="Twitter" class="bg-zinc-950">Twitter (X)</option>
                                        <option value="GitHub" class="bg-zinc-950">GitHub</option>
                                        <option value="Instagram" class="bg-zinc-950">Instagram</option>
                                        <option value="Behance" class="bg-zinc-950">Behance</option>
                                        <option value="Dribbble" class="bg-zinc-950">Dribbble</option>
                                        <option value="Facebook" class="bg-zinc-950">Facebook</option>
                                        <option value="YouTube" class="bg-zinc-950">YouTube</option>
                                        <option value="TikTok" class="bg-zinc-950">TikTok</option>
                                        <option value="Discord" class="bg-zinc-950">Discord</option>
                                        <option value="Threads" class="bg-zinc-950">Threads</option>
                                        <option value="Other" class="bg-zinc-950">Other / Custom Link</option>
                                    </select>
                                    <input type="url" name="urls[]" placeholder="https://profile-link.com" 
                                           class="w-full bg-transparent text-[10px] text-zinc-500 focus:outline-none focus:text-white italic">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="submit" name="add_member" class="group relative px-12 py-4 bg-white text-black font-black uppercase tracking-[0.3em] text-[9px] rounded-full overflow-hidden transition-all hover:scale-105 active:scale-95">
                        <span class="relative z-10">Deploy Profile</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="absolute inset-0 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 z-20 uppercase tracking-widest font-black">Finalize Deployment</span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #18181b; border-radius: 10px; }
    input::placeholder { color: #27272a; font-size: 8px; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; }
</style>

<script>
    function addSocialField() {
        const container = document.getElementById('social-container');
        const groups = document.querySelectorAll('.social-group');
        const newField = groups[0].cloneNode(true);
        
        // Clear inputs in the cloned field
        newField.querySelectorAll('input').forEach(input => input.value = '');
        newField.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        
        // Ensure the remove button is functional for the new field
        container.appendChild(newField);
        
        // Auto-scroll to bottom of links
        container.scrollTop = container.scrollHeight;
    }
</script>