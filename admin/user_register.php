<?php
// 1. Load the Sidebar (Handles Session, DB, Security)
require_once __DIR__ . '/sidebar.php';
// 2. Load the Header (Handles the Navigation and CSS)
require_once __DIR__ . '/../includes/header.php'; 

$message = "";
$show_popup = false; // Flag to trigger popup

// 3. FETCH ALL ADMINS
$admins_stmt = $pdo->query("SELECT surname, other_name, email, telephone, picture FROM admin_users ORDER BY id DESC");
$all_admins = $admins_stmt->fetchAll();

// 4. LOGIC FOR NEW REGISTRATION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $surname    = $_POST['surname'];
    $other_name = $_POST['other_name'];
    $email      = $_POST['email'];
    $telephone  = $_POST['telephone'];
    $gender     = $_POST['gender'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $picture = "default-avatar.png";
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../assets/img/admins/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $surname) . "." . pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_dir . $file_name)) { $picture = $file_name; }
    }

    try {
        $sql = "INSERT INTO admin_users (surname, other_name, email, password, gender, telephone, picture) 
                VALUES (:surname, :other_name, :email, :password, :gender, :telephone, :picture)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['surname'=>$surname, 'other_name'=>$other_name, 'email'=>$email, 'password'=>$password, 'gender'=>$gender, 'telephone'=>$telephone, 'picture'=>$picture]);
        
        // Instead of redirecting via JS, we just set this to true
        $show_popup = true;
        
        // Refresh the admin list so the new person appears immediately
        $admins_stmt = $pdo->query("SELECT surname, other_name, email, telephone, picture FROM admin_users ORDER BY id DESC");
        $all_admins = $admins_stmt->fetchAll();

    } catch (PDOException $e) {
        $message = ($e->getCode() == 23000) ? "Email already exists!" : "Error: " . $e->getMessage();
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="admin-main-content">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-12">
            <h2 class="text-2xl font-black text-white mb-6 uppercase tracking-tighter">Current <span class="text-purple-500">Administrators</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($all_admins as $admin): ?>
                <div class="bg-zinc-900/40 border border-zinc-800 p-4 rounded-2xl flex items-center gap-4">
                    <img src="../assets/img/admins/<?= $admin['picture'] ?>" class="w-12 h-12 rounded-full object-cover border-2 border-purple-500/20">
                    <div>
                        <h4 class="text-white font-bold text-sm"><?= htmlspecialchars($admin['surname'] . ' ' . $admin['other_name']) ?></h4>
                        <p class="text-zinc-500 text-[10px]"><?= htmlspecialchars($admin['email']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <hr class="border-zinc-800 mb-12">

        <div class="bg-zinc-900/40 border border-zinc-800 p-8 rounded-[2.5rem] shadow-2xl mb-20">
            <div class="mb-8">
                <h2 class="text-xl font-black text-white uppercase tracking-widest">Add New Team Member</h2>
                <p class="text-zinc-500 text-[10px]">Fill in the details below to grant system access.</p>
            </div>

            <?php if ($message): ?>
                <div class="bg-red-500/10 text-red-500 border border-red-500/20 p-4 rounded-xl mb-6 text-xs font-bold"><?= $message ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Surname</label>
                        <input type="text" name="surname" required class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none" placeholder="Surname">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Other Names</label>
                        <input type="text" name="other_name" required class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none" placeholder="First & Middle">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none" placeholder="admin@seyidev.com">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Telephone</label>
                        <input type="tel" name="telephone" required class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none" placeholder="+234...">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Gender</label>
                        <select name="gender" class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-black border border-zinc-800 p-3 rounded-xl text-white text-sm focus:border-purple-500 outline-none" placeholder="••••••••">
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest ml-1">Profile Photo</label>
                        <input type="file" name="picture" class="w-full bg-black border border-zinc-800 p-2 rounded-xl text-zinc-500 text-xs file:bg-zinc-800 file:text-white file:border-0 file:px-3 file:py-1 file:rounded-lg cursor-pointer">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full py-3.5 bg-purple-600 rounded-xl text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white hover:text-black transition-all">
                            Add Administrator
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php if ($show_popup): ?>
<script>
    Swal.fire({
        title: 'Success!',
        text: 'New Administrator added successfully.',
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#7c3aed',
        background: '#0d021b',
        color: '#ffffff'
    });
</script>
<?php endif; ?>

</body>
</html>