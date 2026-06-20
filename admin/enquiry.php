<?php
// 1. Load Sidebar & Header
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../includes/header.php';

// 2. HANDLE ACTIONS (Individual Delete & Bulk Delete)
$status_msg = "";

// Single Delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $pdo->prepare("DELETE FROM enquiries_form WHERE id = ?")->execute([$id]);
    header("Location: enquiry.php?msg=deleted");
    exit();
}

// Bulk Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_delete'])) {
    if (!empty($_POST['selected_ids'])) {
        $ids = $_POST['selected_ids']; // Array of IDs
        $placeholder = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "DELETE FROM enquiries_form WHERE id IN ($placeholder)";
        $pdo->prepare($sql)->execute($ids);
        header("Location: enquiry.php?msg=bulk_deleted");
        exit();
    }
}

// 3. FETCH ENQUIRIES
$enquiries = $pdo->query("SELECT * FROM enquiries_form ORDER BY created_at DESC")->fetchAll();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="admin-main-content">
    <div class="max-w-7xl mx-auto">
        
        <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-5xl font-black text-white tracking-tighter uppercase">
                    Client <span class="text-purple-500">Enquiries</span>
                </h1>
                <p class="text-zinc-500 font-medium uppercase text-[10px] tracking-[0.3em] mt-2">
                    Manage project requests and bulk actions
                </p>
            </div>
            
            <button type="button" id="bulkDeleteBtn" onclick="confirmBulkDelete()" class="hidden px-6 py-3 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-900/20">
                Delete Selected (<span id="selectedCount">0</span>)
            </button>
        </header>

        <div class="bg-zinc-900/40 border border-zinc-800 rounded-[2.5rem] overflow-hidden backdrop-blur-md">
            <form id="bulkForm" method="POST">
                <input type="hidden" name="bulk_delete" value="1">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-zinc-500 text-[10px] uppercase tracking-[0.2em] border-b border-zinc-800 bg-zinc-900/50">
                                <th class="px-6 py-6 w-10">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-zinc-700 bg-black text-purple-600 focus:ring-purple-500">
                                </th>
                                <th class="px-6 py-6">Client Info</th>
                                <th class="px-6 py-6">Project Purpose</th>
                                <th class="px-6 py-6">Budget</th>
                                <th class="px-6 py-6">Status</th>
                                <th class="px-6 py-6">Date</th>
                                <th class="px-6 py-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            <?php if (empty($enquiries)): ?>
                                <tr><td colspan="7" class="px-8 py-20 text-center text-zinc-600 italic">No enquiries found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($enquiries as $item): ?>
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-6 py-6">
                                            <input type="checkbox" name="selected_ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4 h-4 rounded border-zinc-700 bg-black text-purple-600 focus:ring-purple-500">
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex flex-col">
                                                <span class="text-white font-bold text-sm"><?= htmlspecialchars($item['full_name']) ?></span>
                                                <span class="text-zinc-500 text-xs"><?= htmlspecialchars($item['email']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <span class="text-zinc-300 text-xs font-medium block truncate max-w-[150px]"><?= htmlspecialchars($item['project_purpose']) ?></span>
                                            <button type="button" onclick='viewDetails(<?= json_encode($item) ?>)' class="text-purple-500 text-[9px] font-black uppercase mt-1">Details</button>
                                        </td>
                                        <td class="px-6 py-6">
                                            <span class="text-green-500 text-[10px] font-bold"><?= htmlspecialchars($item['budget']) ?></span>
                                        </td>
                                        <td class="px-6 py-6">
                                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase border border-blue-500/20 bg-blue-500/10 text-blue-500">
                                                <?= $item['status'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 text-zinc-500 text-xs"><?= date('M d', strtotime($item['created_at'])) ?></td>
                                        <td class="px-6 py-6 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="mailto:<?= $item['email'] ?>" class="w-8 h-8 flex items-center justify-center bg-zinc-800 rounded-lg hover:bg-purple-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></a>
                                                <button type="button" onclick="confirmDelete(<?= $item['id'] ?>)" class="w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</main>



<script>
// 1. Select All Logic
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.row-checkbox');
const bulkBtn = document.getElementById('bulkDeleteBtn');
const countSpan = document.getElementById('selectedCount');

function updateBulkBtn() {
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    countSpan.innerText = checkedCount;
    bulkBtn.classList.toggle('hidden', checkedCount === 0);
}

selectAll.addEventListener('change', (e) => {
    checkboxes.forEach(cb => cb.checked = e.target.checked);
    updateBulkBtn();
});

checkboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkBtn);
});

// 2. SweetAlert Popups
function viewDetails(data) {
    Swal.fire({
        title: 'Enquiry Details',
        html: `<div class="text-left text-zinc-400 text-sm"><p><strong>Description:</strong></p><p class="mt-2 text-white">${data.project_description}</p></div>`,
        background: '#0d021b', confirmButtonColor: '#7c3aed', color: '#fff'
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Enquiry?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#27272a',
        confirmButtonText: 'Yes, delete',
        background: '#0d021b', color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = `enquiry.php?delete_id=${id}`;
    });
}

function confirmBulkDelete() {
    Swal.fire({
        title: 'Bulk Delete?',
        text: `Are you sure you want to delete ${document.querySelectorAll('.row-checkbox:checked').length} items?`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#27272a',
        confirmButtonText: 'Delete All Selected',
        background: '#0d021b', color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) document.getElementById('bulkForm').submit();
    });
}

// Success Messages
<?php if(isset($_GET['msg'])): ?>
    Swal.fire({
        title: 'Success!',
        text: 'Action completed successfully.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: '#0d021b', color: '#fff'
    });
<?php endif; ?>
</script>