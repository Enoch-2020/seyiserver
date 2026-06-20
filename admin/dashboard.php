<?php 
// 1. Load the Sidebar (Handles Session, DB, and HTML Head)
require_once __DIR__ . '/sidebar.php'; 
require_once __DIR__ . '/../includes/header.php'; 

// 2. Fetch Stats for the Dashboard
try {
    // Count total projects
    $stmtProjects = $pdo->query("SELECT COUNT(*) FROM Services");
    $totalProjects = $stmtProjects->fetchColumn();

    // Count total partners
    $stmtClients = $pdo->query("SELECT COUNT(*) FROM Clients");
    $totalPartners = $stmtClients->fetchColumn();

    // Fetch the 5 most recent projects
    $stmtRecent = $pdo->query("SELECT title, category, created_at FROM Services ORDER BY id DESC LIMIT 5");
    $recentProjects = $stmtRecent->fetchAll();

} catch (PDOException $e) {
    $error = "Data fetch failed: " . $e->getMessage();
}
?>

<main class="admin-main-content">
    <div class="max-w-6xl mx-auto">
        
        <header class="mb-12">
            <h1 class="text-5xl font-black text-white tracking-tighter">
                DASHBOARD<span class="text-purple-500">.</span>
            </h1>
            <p class="text-zinc-500 font-medium uppercase text-xs tracking-[0.2em] mt-2">
                Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator') ?>
            </p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-zinc-900/40 border border-zinc-800 p-8 rounded-[2rem] backdrop-blur-sm">
                <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest">Active Projects</p>
                <h3 class="text-5xl font-black text-white mt-4"><?= $totalProjects ?></h3>
                <div class="h-1 w-12 bg-purple-500 mt-4 rounded-full"></div>
            </div>

            <div class="bg-zinc-900/40 border border-zinc-800 p-8 rounded-[2rem] backdrop-blur-sm">
                <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest">Total Partners</p>
                <h3 class="text-5xl font-black text-white mt-4"><?= $totalPartners ?></h3>
                <div class="h-1 w-12 bg-blue-500 mt-4 rounded-full"></div>
            </div>

            <div class="bg-gradient-to-br from-purple-600 to-blue-600 p-8 rounded-[2rem] shadow-xl flex flex-col justify-center">
                <p class="text-white/80 text-[10px] uppercase font-black tracking-widest">Quick Actions</p>
                <a href="product_upload.php" class="mt-4 bg-white text-black text-center py-3 rounded-xl font-bold text-sm hover:bg-black hover:text-white transition">
                    + New Upload
                </a>
            </div>
        </div>

        <div class="bg-zinc-900/40 border border-zinc-800 rounded-[2rem] overflow-hidden">
            <div class="p-8 border-b border-zinc-800 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Recent Portfolio Additions</h2>
                <a href="#" class="text-purple-500 text-xs font-bold uppercase tracking-widest hover:text-white transition">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-zinc-500 text-[10px] uppercase tracking-widest border-b border-zinc-800">
                            <th class="px-8 py-5">Project Name</th>
                            <th class="px-8 py-5">Category</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if (empty($recentProjects)): ?>
                            <tr>
                                <td colspan="3" class="px-8 py-10 text-center text-zinc-600 italic">No projects uploaded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentProjects as $project): ?>
                                <tr class="border-b border-zinc-800/50 hover:bg-white/5 transition">
                                    <td class="px-8 py-6 font-bold text-white"><?= htmlspecialchars($project['title']) ?></td>
                                    <td class="px-8 py-6">
                                        <span class="bg-zinc-800 px-3 py-1 rounded-full text-[10px] text-zinc-300"><?= htmlspecialchars($project['category']) ?></span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="text-zinc-500 hover:text-red-500 transition">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

</body>
</html>