<?php 
require_once __DIR__ . '/includes/db.php'; 
require_once __DIR__ . '/includes/header.php'; 
?>

<style>
    /* Global Animations */
    @keyframes popup-in {
        0% { opacity: 0; transform: translate(-50%, -100px); }
        100% { opacity: 1; transform: translate(-50%, 0); }
    }
    @media (min-width: 768px) {
        @keyframes popup-in { 0% { opacity: 0; transform: translateX(100px); } 100% { opacity: 1; transform: translateX(0); } }
    }
    .animate-popup { animation: popup-in 0.7s cubic-bezier(0.23, 1, 0.32, 1) both; }
    @keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }
    .animate-progress { animation: progress-shrink 5s linear forwards; }
    @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 50% { transform: translate(20px, -30px) scale(1.05); } 100% { transform: translate(0px, 0px) scale(1); } }
    .animate-blob { animation: blob 8s infinite alternate ease-in-out; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3b0764; }
    
    /* Calendar Styling */
    .calendar-day { aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; font-size: 10px; border-radius: 8px; transition: all 0.3s; }
    .calendar-day.active { background: #7c3aed; color: white; box-shadow: 0 0 15px rgba(124, 58, 237, 0.4); }

    /* Extra safety for the main section height */
    #contact-section { min-height: 100vh; display: flex; align-items: center; }
</style>

<?php if (isset($_GET['status'])): 
    $isSuccess = $_GET['status'] === 'success';
?>
<div id="status-popup" class="fixed top-24 left-1/2 -translate-x-1/2 md:left-auto md:right-10 md:translate-x-0 z-[9999] animate-popup">
    <div class="relative overflow-hidden min-w-[340px] backdrop-blur-2xl bg-zinc-950/90 border <?= $isSuccess ? 'border-green-500/30' : 'border-red-500/30' ?> p-5 rounded-[2rem] shadow-2xl">
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

<section id="contact-section" class="py-24 md:py-32 lg:py-40 relative overflow-hidden bg-[#0A0118]">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-purple-600/10 blur-[120px] rounded-full animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-900/10 blur-[120px] rounded-full animate-blob animation-delay-2000"></div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-3 lg:sticky lg:top-32 space-y-8">
                <div>
                    <div class="inline-block px-3 py-1 rounded-full bg-purple-500/10 text-purple-400 text-[9px] font-black uppercase tracking-[0.3em] mb-4 border border-purple-500/20">Contact</div>
                    <h2 class="text-4xl font-black text-white mb-4 leading-tight">Let's build <br><span class="text-purple-500">Legendary.</span></h2>
                    <p class="text-slate-400 text-sm leading-relaxed">I help visionary brands turn complex ideas into high-performance digital products.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 backdrop-blur-sm">
                        <span class="text-purple-500 mt-1">📍</span>
                        <div>
                            <p class="text-white text-xs font-bold uppercase tracking-tighter">Location</p>
                            <p class="text-slate-400 text-xs">Ijebu-Ode, Ogun State, Nigeria</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 backdrop-blur-sm">
                        <span class="text-purple-500 mt-1">📧</span>
                        <div>
                            <p class="text-white text-xs font-bold uppercase tracking-tighter">Email</p>
                            <p class="text-slate-400 text-xs">support@seyimultiservice.com.ng</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-white/[0.02] backdrop-blur-3xl p-8 rounded-[2.5rem] shadow-2xl border border-white/[0.05]">
                    <div class="mb-8">
                        <h4 class="text-xl font-bold text-white mb-1">Project Brief</h4>
                        <p class="text-slate-500 text-sm">Provide details to get an accurate quote.</p>
                    </div>
                    
                    <form action="includes/submit_logic.php" method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="relative group">
                                <input type="text" name="full_name" required placeholder=" " class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-all" />
                                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-purple-500">Full Name</label>
                            </div>
                            <div class="relative group">
                                <select name="budget" required class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 cursor-pointer">
                                    <option value="" disabled selected class="bg-[#0A0118]">Budget Range</option>
                                     <option value="Below" class="bg-[#0A0118]">Below $500</option>
                                    <option value="500" class="bg-[#0A0118]">$500 - $1,000</option>
                                    <option value="1000" class="bg-[#0A0118]">$1,000 - $5,000</option>
                                    <option value="5000" class="bg-[#0A0118]">$5,000+</option>
                                </select>
                                <label class="absolute left-0 -top-3.5 text-purple-500 text-[10px] uppercase font-bold">Budget</label>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="relative group">
                                <input type="email" name="email" required placeholder=" " class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-all" />
                                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-purple-500">Email Address</label>
                            </div>
                            <div class="relative group">
                                <input type="tel" name="phone" required placeholder=" " class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-all" />
                                <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-purple-500">Phone</label>
                            </div>
                        </div>

                        <div class="relative group">
                            <input type="text" name="project_purpose" required placeholder=" " class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none focus:border-purple-500 transition-all" />
                            <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold">Project Purpose</label>
                        </div>

                        <div class="relative group">
                            <textarea name="project_description" required placeholder=" " class="peer w-full bg-transparent border-b border-zinc-800 py-2 text-sm text-white outline-none h-20 resize-none custom-scrollbar transition-all"></textarea>
                            <label class="absolute left-0 -top-3.5 text-zinc-500 text-[10px] uppercase font-bold">Description</label>
                        </div>
                        
                        <button type="submit" name="submit_inquiry" class="w-full bg-white text-black py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-purple-600 hover:text-white transition-all shadow-lg active:scale-95">
                            Launch Project Inquiry
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3 lg:sticky lg:top-32 space-y-8">
                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-[2rem] backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-white text-[10px] font-black uppercase tracking-widest">Availability</h5>
                        <span class="text-green-500 text-[8px] animate-pulse">● LIVE</span>
                    </div>
                    
                    <div class="text-white text-center mb-2 font-bold text-xs"><?= date('F Y') ?></div>
                    <div class="grid grid-cols-7 gap-1">
                        <?php 
                        $days = ["S","M","T","W","T","F","S"];
                        foreach($days as $d) echo "<div class='text-[8px] text-zinc-600 text-center font-bold'>$d</div>";
                        
                        $today = (int)date('d');
                        for($i=1; $i<=31; $i++) {
                            $active = ($i == $today) ? 'active' : ''; 
                            echo "<div class='calendar-day $active text-zinc-400'>$i</div>";
                        }
                        ?>
                    </div>
                    <p class="text-[9px] text-zinc-500 mt-4 text-center italic">Currently accepting new projects for Q1 2026.</p>
                </div>

                <div class="p-6 rounded-[2rem] bg-gradient-to-br from-purple-600/20 to-transparent border border-purple-500/20 backdrop-blur-sm">
                    <h5 class="text-white text-[10px] font-black uppercase tracking-widest mb-4">Quick Chat</h5>
                    <a href="https://wa.link/me3ipi" target="_blank" class="flex items-center justify-between group">
                        <span class="text-slate-300 text-xs group-hover:text-white transition-colors">WhatsApp</span>
                        <span class="text-purple-500 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                    <hr class="my-3 border-white/5">
                    <a href="https://www.linkedin.com/in/kazeem-kabiru-477abbb6/" target="_blank" class="flex items-center justify-between group">
                        <span class="text-slate-300 text-xs group-hover:text-white transition-colors">LinkedIn</span>
                        <span class="text-purple-500 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>