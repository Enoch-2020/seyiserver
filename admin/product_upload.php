<?php 
require_once __DIR__ . '/sidebar.php'; 
require_once __DIR__ . '/../includes/header.php'; 

$message = "";

// 1. BULK & INDIVIDUAL DELETE LOGIC FOR PROJECTS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_delete_projects'])) {
    if (!empty($_POST['project_ids'])) {
        $ids = $_POST['project_ids'];
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        // Cleanup Files
        $stmt = $pdo->prepare("SELECT feature_image FROM Services WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $file) {
            if ($file && file_exists("../" . $file)) unlink("../" . $file);
        }

        // Delete Database Records
        $stmt = $pdo->prepare("DELETE FROM Services WHERE id IN ($placeholders)");
        if ($stmt->execute($ids)) {
            $message = "<div class='bg-orange-500/20 border border-orange-500 text-orange-400 p-4 rounded-xl mb-6'>🗑️ Selected projects deleted!</div>";
        }
    }
}

// 2. BULK & INDIVIDUAL DELETE LOGIC FOR PARTNERS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_delete_partners'])) {
    if (!empty($_POST['partner_ids'])) {
        $ids = $_POST['partner_ids'];
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        // Cleanup Files
        $stmt = $pdo->prepare("SELECT client_logo_url FROM Clients WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $file) {
            if ($file && file_exists("../" . $file)) unlink("../" . $file);
        }

        // Delete Database Records
        $stmt = $pdo->prepare("DELETE FROM Clients WHERE id IN ($placeholders)");
        if ($stmt->execute($ids)) {
            $message = "<div class='bg-blue-500/20 border border-blue-500 text-blue-400 p-4 rounded-xl mb-6'>🗑️ Selected partners removed!</div>";
        }
    }
}

// 3. PROJECT UPLOAD LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_project'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $about = $_POST['about_product'];
    
    $target_dir = "../assets/img/portfolio/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_name = time() . '_' . basename($_FILES["feature_image"]["name"]);
    $target_file = $target_dir . $file_name;
    $db_save_path = "assets/img/portfolio/" . $file_name;
    
    if (move_uploaded_file($_FILES["feature_image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO Services (title, category, description, feature_image, link, about_product) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $description, $db_save_path, $link, $about]);
        $message = "<div class='bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-xl mb-6'>🚀 Project published!</div>";
    }
}

// 4. PARTNER UPLOAD LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_client'])) {
    $client_name = $_POST['client_name'];
    $website_url = $_POST['website_url'];
    
    $target_dir = "../assets/img/clients/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_name = "logo_" . time() . '_' . basename($_FILES["client_logo"]["name"]);
    $target_file = $target_dir . $file_name;
    $db_save_path = "assets/img/clients/" . $file_name;
    
    if (move_uploaded_file($_FILES["client_logo"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO Clients (client_name, client_logo_url, website_url, is_active) VALUES (?, ?, ?, 1)";
        $pdo->prepare($sql)->execute([$client_name, $db_save_path, $website_url]);
        $message = "<div class='bg-blue-500/20 border border-blue-500 text-blue-400 p-4 rounded-xl mb-6'>🤝 Partner added!</div>";
    }
}

// Fetch Data
$all_projects = $pdo->query("SELECT * FROM Services ORDER BY id DESC")->fetchAll();
$all_partners = $pdo->query("SELECT * FROM Clients ORDER BY id DESC")->fetchAll();
$existing_categories = $pdo->query("SELECT DISTINCT category FROM Services ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<main class="admin-main-content pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-0">
        
        <?= $message; ?>

        <div class="bg-zinc-900/40 border border-zinc-800 p-8 rounded-[3rem] backdrop-blur-md mb-12">
            <form action="" method="POST" onsubmit="return confirm('Delete selected projects?')">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h1 class="text-3xl font-black text-white">Live <span class="text-orange-500">Portfolio.</span></h1>
                        <p class="text-slate-400 font-medium uppercase text-[9px] tracking-[0.3em]">Management</p>
                    </div>
                    <button type="submit" name="bulk_delete_projects" class="px-6 py-2 bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                        Delete Selected
                    </button>
                </div>

                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                    <?php foreach ($all_projects as $proj): ?>
                        <label class="flex items-center justify-between bg-zinc-950/50 border border-zinc-800 p-4 rounded-2xl cursor-pointer hover:border-zinc-600 transition-all">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" name="project_ids[]" value="<?= $proj['id'] ?>" class="w-4 h-4 rounded border-zinc-700 bg-zinc-900 text-purple-600 focus:ring-purple-500">
                                <img src="../<?= htmlspecialchars($proj['feature_image']) ?>" class="w-10 h-10 object-cover rounded-lg" alt="">
                                <div>
                                    <h4 class="text-white font-bold text-xs"><?= htmlspecialchars($proj['title']) ?></h4>
                                    <span class="text-[8px] uppercase tracking-widest text-purple-500 font-black"><?= htmlspecialchars($proj['category']) ?></span>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>

        <div class="bg-zinc-900/40 border border-zinc-800 p-8 md:p-12 rounded-[3rem] backdrop-blur-md mb-12">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-black text-white">Add New <span class="text-purple-500">Project.</span></h2>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <input type="text" name="title" placeholder="Project Name" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-purple-500 outline-none text-sm">
                    <input list="category-list" name="category" placeholder="Category" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-purple-500 outline-none text-sm">
                    <datalist id="category-list">
                        <?php foreach ($existing_categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <input type="text" name="description" placeholder="Brief Summary" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-purple-500 outline-none text-sm">
                <textarea name="about_product" rows="4" placeholder="Full Case Study Details..." required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-purple-500 outline-none text-sm custom-scrollbar"></textarea>
                <div class="grid md:grid-cols-2 gap-6">
                    <input type="url" name="link" placeholder="Live URL (https://...)" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-purple-500 outline-none text-sm">
                    <input type="file" name="feature_image" accept="image/*" required class="w-full bg-zinc-950 border border-zinc-800 p-3 rounded-2xl text-[10px] text-slate-400 file:bg-purple-600 file:text-white file:rounded-full file:border-0 file:px-4 cursor-pointer">
                </div>
                <button type="submit" name="submit_project" class="w-full py-4 bg-purple-600 rounded-2xl text-white font-black text-sm uppercase tracking-widest hover:bg-purple-700 transition-all">Publish Project</button>
            </form>
        </div>

        <div class="bg-zinc-900/40 border border-zinc-800 p-8 rounded-[3rem] backdrop-blur-md mb-12">
            <form action="" method="POST" onsubmit="return confirm('Remove selected partners?')">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h1 class="text-3xl font-black text-white">Brand <span class="text-blue-500">Partners.</span></h1>
                        <p class="text-slate-400 font-medium uppercase text-[9px] tracking-[0.3em]">Collaborations</p>
                    </div>
                    <button type="submit" name="bulk_delete_partners" class="px-6 py-2 bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                        Remove Selected
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[300px] overflow-y-auto pr-4 custom-scrollbar">
                    <?php foreach ($all_partners as $client): ?>
                        <label class="flex items-center gap-4 bg-zinc-950/50 border border-zinc-800 p-3 rounded-2xl cursor-pointer hover:border-blue-500/50 transition-all">
                            <input type="checkbox" name="partner_ids[]" value="<?= $client['id'] ?>" class="w-4 h-4 rounded border-zinc-700 bg-zinc-900 text-blue-600 focus:ring-blue-500">
                            <img src="../<?= htmlspecialchars($client['client_logo_url']) ?>" class="w-12 h-8 object-contain grayscale" alt="">
                            <span class="text-white font-bold text-xs"><?= htmlspecialchars($client['client_name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>

        <div class="bg-zinc-900/40 border border-zinc-800 p-8 md:p-12 rounded-[3rem] backdrop-blur-md">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-black text-white">Add New <span class="text-blue-500">Partner.</span></h2>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <input type="text" name="client_name" placeholder="Brand Name (e.g. Nike)" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-blue-500 outline-none text-sm">
                    <input type="url" name="website_url" placeholder="Website Link" required class="w-full bg-zinc-950 border border-zinc-800 p-4 rounded-2xl text-white focus:border-blue-500 outline-none text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] text-zinc-500 uppercase font-black tracking-widest ml-4">Brand Logo (Transparent PNG Preferred)</label>
                    <input type="file" name="client_logo" accept="image/*" required class="w-full bg-zinc-950 border border-zinc-800 p-3 rounded-2xl text-[10px] text-slate-400 file:bg-blue-600 file:text-white file:rounded-full file:border-0 file:px-4 cursor-pointer">
                </div>
                <button type="submit" name="submit_client" class="w-full py-4 bg-blue-600 rounded-2xl text-white font-black text-sm uppercase tracking-widest hover:bg-blue-700 transition-all">Register Partner</button>
            </form>
        </div>

    </div>
</main>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
</style>

</body>
</html>