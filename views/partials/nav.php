<?php
    $current_user = wp_get_current_user();
    $user_name = $current_user->exists() ? $current_user->display_name : 'Invitado';
    $user_letter = strtoupper(substr($user_name, 0, 1));
?>
<nav class="bg-[#0a0a0a] border-b border-[#222222] sticky top-0 z-[100]">
    <div class="max-w-7xl mx-auto px-4 md:px-8 h-20 flex items-center justify-between">
        
        <!-- LOGO & TITLE -->
        <div class="flex items-center gap-4">
            <div class="bg-[#ff5100] p-2 rounded-xl text-white hidden sm:block">
                <i data-lucide="rocket" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-lg md:text-xl font-black tracking-tight leading-none text-white">Pensar <span class="text-[#ff5100]">BIG</span></h1>
                <p class="text-[9px] md:text-[10px] font-bold text-[#a3a3a3] uppercase tracking-widest mt-1 hidden sm:block">Dashboard Dinámico</p>
            </div>
            
            <!-- CAMPAIGN SELECTOR DESKTOP -->
            <div class="ml-2 md:ml-4 hidden sm:block">
                <select id="active-campaign-selector" onchange="window.changeCampaign(this.value)" class="bg-[#111] border border-[#333] rounded-lg px-2 py-1 font-bold text-xs md:text-sm text-[#ff5100] outline-none cursor-pointer">
                    <option value="">Cargando...</option>
                </select>
            </div>
        </div>

        <!-- DESKTOP MENU -->
        <div class="hidden md:flex items-center gap-1 bg-[#111111] border border-[#333333] p-1.5 rounded-3xl">
            <button onclick="window.switchTab('stats')" id="btn-stats" class="nav-btn tab-active text-white px-4 py-2 rounded-2xl flex items-center gap-2 font-medium text-sm transition-all"><i data-lucide="pie-chart" class="w-4 h-4"></i> Estadísticas</button>
            <button onclick="window.switchTab('entry')" id="btn-entry" class="nav-btn text-[#a3a3a3] hover:text-white px-4 py-2 rounded-2xl flex items-center gap-2 font-medium text-sm transition-all"><i data-lucide="plus-circle" class="w-4 h-4"></i> Reportar</button>
            <button onclick="window.switchTab('structure')" id="btn-structure" class="nav-btn text-[#a3a3a3] hover:text-white px-4 py-2 rounded-2xl flex items-center gap-2 font-medium text-sm transition-all"><i data-lucide="layout-grid" class="w-4 h-4"></i> Estructura</button>
            <button onclick="window.switchTab('config')" id="btn-config" class="nav-btn text-[#a3a3a3] hover:text-white px-4 py-2 rounded-2xl flex items-center gap-2 font-medium text-sm transition-all"><i data-lucide="settings-2" class="w-4 h-4"></i> Config</button>
        </div>

        <!-- USER & AUTH -->
        <div class="flex items-center gap-2 md:gap-3">
            <div class="flex items-center bg-[#111111] border border-[#222222] px-2 py-1.5 md:px-3 rounded-2xl gap-2 md:gap-3">
                <div class="flex items-center gap-2 border-r border-[#333] pr-2 md:pr-3">
                    <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-[#ff5100] flex items-center justify-center text-[10px] md:text-xs font-bold text-white shadow-lg">
                        <?php echo esc_html($user_letter); ?>
                    </div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-300 hidden md:block"><?php echo esc_html($user_name); ?></span>
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <span id="auth-status" class="text-[9px] md:text-[10px] font-bold text-[#a3a3a3] font-mono tracking-tighter hidden md:block">...</span>
                    <div class="w-1.5 h-1.5 md:w-2 md:h-2 bg-[#ff5100] rounded-full animate-pulse" title="SQL Conectado"></div>
                </div>
            </div>

            <!-- Hamburger Button for Mobile -->
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden text-white p-1 hover:text-[#ff5100]">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU DROPDOWN -->
    <div id="mobile-menu" class="hidden md:hidden bg-[#0a0a0a] border-b border-[#222222] absolute w-full left-0 top-20 shadow-2xl">
        <div class="px-4 py-4 flex flex-col gap-2">
            <label class="text-[10px] font-bold text-[#a3a3a3] uppercase tracking-widest sm:hidden">Campaña Activa</label>
            <select id="mobile-active-campaign-selector" onchange="window.changeCampaign(this.value);" class="bg-[#111] border border-[#333] rounded-lg px-3 py-2 font-bold text-sm text-[#ff5100] outline-none cursor-pointer mb-2 sm:hidden">
                <option value="">Cargando Campañas...</option>
            </select>
            
            <button onclick="window.switchTab('stats'); document.getElementById('mobile-menu').classList.add('hidden')" class="text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl bg-[#111] hover:bg-[#222] flex items-center gap-3 font-medium text-sm transition-all"><i data-lucide="pie-chart" class="w-4 h-4 text-[#ff5100]"></i> Estadísticas</button>
            <button onclick="window.switchTab('entry'); document.getElementById('mobile-menu').classList.add('hidden')" class="text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl bg-[#111] hover:bg-[#222] flex items-center gap-3 font-medium text-sm transition-all"><i data-lucide="plus-circle" class="w-4 h-4 text-[#ff5100]"></i> Reportar Formulario</button>
            <button onclick="window.switchTab('structure'); document.getElementById('mobile-menu').classList.add('hidden')" class="text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl bg-[#111] hover:bg-[#222] flex items-center gap-3 font-medium text-sm transition-all"><i data-lucide="layout-grid" class="w-4 h-4 text-[#ff5100]"></i> Estructura de Proyecto</button>
            <button onclick="window.switchTab('config'); document.getElementById('mobile-menu').classList.add('hidden')" class="text-left text-[#a3a3a3] hover:text-white px-4 py-3 rounded-xl bg-[#111] hover:bg-[#222] flex items-center gap-3 font-medium text-sm transition-all"><i data-lucide="settings-2" class="w-4 h-4 text-[#ff5100]"></i> Configuración Adicional</button>
        </div>
    </div>
</nav>
