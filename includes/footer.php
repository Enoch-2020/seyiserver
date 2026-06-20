<footer class="relative pt-16 pb-8 overflow-hidden" style="background: radial-gradient(circle at top, #1a0b2e 0%, #05010a 100%);">
        
        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-purple-500/20 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-10 md:px-16"> <div class="flex flex-col md:flex-row items-center md:items-stretch">
                
                <div class="flex-1 py-8 flex flex-col items-center md:items-start" data-aos="fade-up">
                    <h4 class="text-purple-500 font-black uppercase text-[10px] tracking-[0.4em] mb-6">Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="About.php" class="text-zinc-400 hover:text-white transition-all text-sm flex items-center gap-3 group">
                            <span class="w-1 h-1 bg-purple-600 rounded-full"></span> About Us
                        </a></li>
                        <li><a href="Portfolio.php" class="text-zinc-400 hover:text-white transition-all text-sm flex items-center gap-3 group">
                            <span class="w-1 h-1 bg-purple-600 rounded-full"></span> Portfolio
                        </a></li>
                        <li><a href="Contact.php" class="text-zinc-400 hover:text-white transition-all text-sm flex items-center gap-3 group">
                            <span class="w-1 h-1 bg-purple-600 rounded-full"></span> Contact Us
                        </a></li>
                    </ul>
                </div>

                <div class="flex-1 py-8 border-y md:border-y-0 md:border-x border-white/5 flex flex-col items-center justify-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center mb-4">
                        <p id="clock-label" class="text-white/60 text-[9px] uppercase tracking-[0.5em] font-black mb-2">Nigeria (Lagos)</p>
                        <div id="digital-clock" class="text-5xl font-black text-white tracking-tighter tabular-nums drop-shadow-[0_0_20px_rgba(168,85,247,0.3)]">00:00:00</div>
                    </div>
                    
                    <div class="relative">
                        <select id="timezone-selector" class="bg-purple-950/30 border border-white/5 text-zinc-400 text-[9px] uppercase font-bold tracking-[0.2em] rounded-full px-6 py-2 focus:outline-none focus:border-purple-500 cursor-pointer appearance-none text-center hover:bg-purple-900/40 transition-all">
                            <optgroup label="Africa" class="bg-zinc-950">
                                <option value="Africa/Lagos" selected>🇳🇬 Nigeria (WAT)</option>
                                <option value="Africa/Johannesburg">🇿🇦 South Africa</option>
                            </optgroup>
                            <optgroup label="Americas" class="bg-zinc-950">
                                <option value="America/New_York">🇺🇸 New York</option>
                                <option value="America/Los_Angeles">🇺🇸 Los Angeles</option>
                            </optgroup>
                            <optgroup label="Europe" class="bg-zinc-950">
                                <option value="Europe/London">🇬🇧 London</option>
                                <option value="Europe/Paris">🇫🇷 Paris</option>
                            </optgroup>
                            <optgroup label="Asia/Oceania" class="bg-zinc-950">
                                <option value="Asia/Dubai">🇦🇪 Dubai</option>
                                <option value="Asia/Tokyo">🇯🇵 Tokyo</option>
                                <option value="Australia/Sydney">🇦🇺 Sydney</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="flex-1 py-8 flex flex-col items-center md:items-end" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-purple-500 font-black uppercase text-[10px] tracking-[0.4em] mb-6">Systems</h4>
                    <ul class="space-y-3 text-center md:text-right">
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <li><a href="admin/dashboard.php" class="text-zinc-400 hover:text-white transition-all text-sm block">Control Dashboard</a></li>
                            <li><a href="admin/enquiry.php" class="text-zinc-400 hover:text-white transition-all text-sm block">Manage Enquiries</a></li>
                        <?php else: ?>
                            <li><a href="admin/login.php" class="text-zinc-400 hover:text-white transition-all text-sm block">Administrative Login</a></li>
                        <?php endif; ?>
                        <li class="flex items-center gap-2 justify-center md:justify-end pt-2">
                            <span class="text-zinc-600 text-[9px] uppercase tracking-widest font-black italic">System: Online</span>
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-4 pt-6 border-t border-white/5 text-center">
                <p class="text-zinc-600 text-[9px] tracking-[0.6em] uppercase font-bold">
                    &copy; <?php echo date("Y"); ?> SeyiDev <span class="mx-3 text-white/5">|</span> 
                    <span class="text-purple-500/40">Digital Excellence</span>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        /* AOS is re-initialized by page-specific scripts with better settings on pages that need it.
           This serves as the baseline for pages that don't have their own init. */
        var _isMobile = window.matchMedia('(max-width:768px)').matches;
        AOS.init({
          once: true,
          duration: _isMobile ? 650 : 900,
          offset:   _isMobile ? 30 : 80,
          easing: 'ease-out-cubic'
        });

        function updateClock() {
            const selector = document.getElementById('timezone-selector');
            const clockDisplay = document.getElementById('digital-clock');
            const labelDisplay = document.getElementById('clock-label');
            
            const selectedTimezone = selector.value;
            const fullText = selector.options[selector.selectedIndex].text;
            const cleanLabel = fullText.split(')')[0].replace(/[^a-zA-Z\s]/g, '').trim();

            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB', { 
                timeZone: selectedTimezone, 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });

            clockDisplay.innerHTML = timeString;
            labelDisplay.innerHTML = cleanLabel;
        }

        document.getElementById('timezone-selector').addEventListener('change', updateClock);
        setInterval(updateClock, 1000);
        updateClock(); 
    </script>
  </body>
</html>