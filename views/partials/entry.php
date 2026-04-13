        <div id="section-entry" class="app-section hidden space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulario Meta Ads -->
                <div class="form-card">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <i data-lucide="facebook" class="text-[#ff5100]"></i> Resultados Meta Ads
                    </h2>
                    <form id="form-entry-ads" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Fecha</label>
                            <input type="date" id="ads-date" required class="w-full p-4 bg-[#0a0a0a] text-white border border-[#333333] rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Conjunto de Anuncio (Ad Set)</label>
                            <select id="ads-adset" required class="w-full p-4 bg-[#0a0a0a] text-white border border-[#333333] rounded-2xl outline-none focus:border-[#ff5100] font-bold"></select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#1a1a1a] border border-[#333333] p-5 rounded-3xl space-y-1">
                                <label class="text-[9px] font-black uppercase text-[#a3a3a3]">Gasto Ads ($)</label>
                                <input type="number" step="0.01" id="ads-spend" required placeholder="0.00" class="w-full bg-transparent text-white outline-none font-black text-xl placeholder-[#555]">
                            </div>
                            <div class="bg-[#1a1a1a] border border-[#ff5100]/30 p-5 rounded-3xl space-y-1">
                                <label class="text-[9px] font-black uppercase text-[#ff5100]">Leads Ads</label>
                                <input type="number" id="ads-leads" required placeholder="0" class="w-full bg-transparent text-[#ff5100] outline-none font-black text-xl placeholder-[#555]">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-[#ff5100] text-white py-5 rounded-[2rem] font-black text-lg hover:bg-[#cc4200] transition-all shadow-lg shadow-[#ff5100]/20">Guardar Datos Ads</button>
                    </form>
                </div>

                <!-- Formulario Ventas -->
                <div class="form-card">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <i data-lucide="ticket" class="text-white"></i> Registro de Entradas
                    </h2>
                    <form id="form-entry-sales" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Fecha de Venta</label>
                            <input type="date" id="sales-date" required class="w-full p-4 bg-[#0a0a0a] text-white border border-[#333333] rounded-2xl outline-none focus:border-white font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-2">Tipo de Entrada</label>
                            <select id="sales-type" required class="w-full p-4 bg-[#0a0a0a] text-white border border-[#333333] rounded-2xl outline-none focus:border-white font-bold">
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#1a1a1a] border border-[#333333] p-5 rounded-3xl space-y-1">
                                <label class="text-[9px] font-black uppercase text-white">Cantidad Vendida</label>
                                <input type="number" id="sales-qty" required placeholder="0" class="w-full bg-transparent text-white outline-none font-black text-xl placeholder-[#555]">
                            </div>
                            <div class="bg-[#1a1a1a] border border-[#333333] p-5 rounded-3xl space-y-1">
                                <label class="text-[9px] font-black uppercase text-[#a3a3a3]">Cortesías</label>
                                <input type="number" id="sales-complimentaries" required placeholder="0" class="w-full bg-transparent text-[#a3a3a3] outline-none font-black text-xl placeholder-[#555]">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-white text-black py-5 rounded-[2rem] font-black text-lg hover:bg-[#e0e0e0] transition-all shadow-lg shadow-white/10">Guardar Venta</button>
                    </form>
                </div>
            </div>
        </div>
