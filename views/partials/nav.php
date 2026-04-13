<?php
    $current_user = wp_get_current_user();
    $user_name = $current_user->exists() ? $current_user->display_name : 'Invitado';
    $user_letter = strtoupper(substr($user_name, 0, 1));
?>
<!-- OVERLAY MOBILE -->
<div id="mobile-overlay" onclick="window.toggleSidebar()" class="fixed inset-0 bg-black/80 z-[140] hidden md:hidden"></div>

<!-- SIDEBAR -->
<nav id="lateral-sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-[#0a0a0a] border-r border-[#222222] z-[150] shadow-2xl transition-transform -translate-x-full md:translate-x-0 flex flex-col shrink-0">
    <!-- LOGO -->
    <div class="h-20 flex items-center px-6 border-b border-[#222]">
        <div class="bg-[#ff5100] p-2 rounded-xl text-white mr-3">
            <i data-lucide="rocket" class="w-5 h-5"></i>
        </div>
        <div>
            <h1 class="text-xl font-black tracking-tight leading-none text-white">Pensar <span class="text-[#ff5100]">BIG</span></h1>
            <p class="text-[10px] font-bold text-[#a3a3a3] uppercase tracking-widest mt-1">Dashboard</p>
        </div>
    </div>

    <!-- MENU ITEMS -->
    <div class="flex-1 py-8 px-4 flex flex-col gap-2 overflow-y-auto">
        <label class="text-[10px] font-bold text-[#555] uppercase tracking-widest px-2 mb-2">Panel Operativo</label>
        
        <button onclick="window.switchTab('campaigns_hub'); window.toggleSidebar()" id="btn-campaigns_hub" class="nav-btn text-left px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm transition-all tab-active text-white bg-[#111] hover:bg-[#111]">
            <i data-lucide="activity" class="w-4 h-4 text-[#ff5100]"></i> Control General
        </button>
        <button onclick="window.switchTab('stats'); window.toggleSidebar()" id="btn-stats" class="nav-btn text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl hover:bg-[#111] flex items-center gap-3 font-medium text-sm transition-all">
            <i data-lucide="pie-chart" class="w-4 h-4 text-[#ff5100]"></i> Estadísticas
        </button>
        <button onclick="window.switchTab('entry'); window.toggleSidebar()" id="btn-entry" class="nav-btn text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl hover:bg-[#111] flex items-center gap-3 font-medium text-sm transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4 text-[#ff5100]"></i> Cargar Entradas
        </button>
        
        <label class="text-[10px] font-bold text-[#555] uppercase tracking-widest px-2 mb-2 mt-6">Administración</label>
        <button onclick="window.switchTab('structure'); window.toggleSidebar()" id="btn-structure" class="nav-btn text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl hover:bg-[#111] flex items-center gap-3 font-medium text-sm transition-all">
            <i data-lucide="layout-grid" class="w-4 h-4 text-[#ff5100]"></i> Estructura Cmp.
        </button>
        <button onclick="window.switchTab('config'); window.toggleSidebar()" id="btn-config" class="nav-btn text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl hover:bg-[#111] flex items-center gap-3 font-medium text-sm transition-all">
            <i data-lucide="settings-2" class="w-4 h-4 text-[#ff5100]"></i> Configuración General
        </button>
    </div>

    <!-- USER BOTTOM -->
    <div class="p-4 border-t border-[#222]">
        <div class="flex items-center gap-3 bg-[#111] p-3 rounded-2xl border border-[#333]">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#ff5100] to-[#ffaa00] flex items-center justify-center text-sm font-bold text-white shadow-lg overflow-hidden shrink-0">
                <?php echo esc_html($user_letter); ?>
            </div>
            <div class="flex flex-col truncate">
                <span class="text-xs font-bold text-white truncate w-full"><?php echo esc_html($user_name); ?></span>
                <span class="text-[9px] text-green-500 uppercase font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div> Online</span>
            </div>
        </div>
    </div>
</nav>

<script>
    window.toggleSidebar = () => {
        const sb = document.getElementById('lateral-sidebar');
        const ov = document.getElementById('mobile-overlay');
        if (sb.classList.contains('-translate-x-full')) {
            sb.classList.remove('-translate-x-full');
            ov.classList.remove('hidden');
        } else {
            // Only hide if we are on mobile screen (not forcing hide on desktop)
            if(window.innerWidth < 768) {
                sb.classList.add('-translate-x-full');
                ov.classList.add('hidden');
            }
        }
    };
    
    // Auto-hide when resizing window
    window.addEventListener('resize', () => {
        const sb = document.getElementById('lateral-sidebar');
        if(window.innerWidth >= 768) {
            sb.classList.remove('-translate-x-full');
            document.getElementById('mobile-overlay').classList.add('hidden');
        } else {
            sb.classList.add('-translate-x-full');
        }
    });
</script>
