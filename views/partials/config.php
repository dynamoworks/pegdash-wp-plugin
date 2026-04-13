        <div id="section-config" class="app-section hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-[#111111] p-10 rounded-[3rem] border border-[#222222] shadow-sm">
                <h3 class="text-xl font-black mb-8 flex items-center gap-3 text-white"><i data-lucide="flag" class="text-[#ff5100]"></i> Gestión de Campañas</h3>
                <form id="form-campaign" class="space-y-6 mb-10">
                    <div>
                        <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Nombre de Campaña</label>
                        <input type="text" id="camp-name" required placeholder="Lanzamiento Mayo 2024" class="w-full p-4 bg-[#0a0a0a] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Meta de Ventas (Entradas)</label>
                        <input type="number" id="camp-goal" required placeholder="500" class="w-full p-4 bg-[#0a0a0a] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                    </div>
                    <button type="submit" class="w-full bg-[#ff5100] text-white py-4 rounded-2xl font-black hover:bg-[#cc4200] transition-all">Crear Campaña</button>
                </form>
                <div id="campaign-list" class="space-y-3"></div>
            </div>

            <div class="bg-[#111111] p-10 rounded-[3rem] border border-[#222222] shadow-sm">
                <h3 class="text-xl font-black mb-8 flex items-center gap-3 text-white"><i data-lucide="tag" class="text-[#ff5100]"></i> Categorías de Estrategia</h3>
                <form id="form-class" class="flex gap-2 mb-8">
                    <input type="text" id="class-name" required placeholder="Ej: Público Frío" class="flex-1 p-4 bg-[#0a0a0a] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                    <input type="color" id="class-color" value="#ff5100" class="w-16 h-14 p-1 bg-[#0a0a0a] border border-[#333] rounded-2xl cursor-pointer">
                    <button type="submit" class="bg-white text-black px-4 rounded-2xl hover:bg-[#e0e0e0]"><i data-lucide="plus"></i></button>
                </form>
                <div id="class-list" class="space-y-3"></div>
            </div>

            <div class="bg-[#111111] p-10 rounded-[3rem] border border-[#222222] shadow-sm">
                <h3 class="text-xl font-black mb-8 flex items-center gap-3 text-white"><i data-lucide="ticket" class="text-[#ff5100]"></i> Tipos de Entrada</h3>
                <form id="form-ticket-type" class="flex gap-2 mb-8">
                    <input type="text" id="ticket-type-name" required placeholder="Ej: Cortesía..." class="flex-1 p-4 bg-[#0a0a0a] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                    <button type="submit" class="bg-white text-black px-4 rounded-2xl hover:bg-[#e0e0e0]"><i data-lucide="plus"></i></button>
                </form>
                <div id="ticket-type-list" class="space-y-3"></div>
            </div>
        </div>
