    <nav class="bg-[#0a0a0a] border-b border-[#222222] sticky top-0 z-[100] px-4 md:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-[#ff5100] p-2.5 rounded-2xl text-white">
                <i data-lucide="rocket" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-tight leading-none text-white">Pensar <span class="text-[#ff5100]">BIG</span></h1>
                <p class="text-[10px] font-bold text-[#a3a3a3] uppercase tracking-widest mt-1">Niveles de Campaña</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden md:flex flex-col text-right">
                <span class="text-[9px] font-black text-[#a3a3a3] uppercase">Campaña Activa</span>
                <select id="active-campaign-selector" onchange="window.changeCampaign(this.value)" class="bg-transparent font-bold text-sm outline-none text-[#ff5100] cursor-pointer">
                    <option value="">Cargando...</option>
                </select>
            </div>
            <div class="h-8 w-[1px] bg-[#333333] mx-2 hidden md:block"></div>
            <div class="bg-[#111111] border border-[#222222] px-4 py-2 rounded-2xl flex items-center gap-3">
                <span id="auth-status" class="text-[10px] font-bold text-[#a3a3a3] font-mono tracking-tighter">ID: ...</span>
                <div class="w-2 h-2 bg-[#ff5100] rounded-full animate-pulse"></div>
            </div>
        </div>
    </nav>
