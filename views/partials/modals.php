    <!-- MODALES -->
    <div id="modal-adset" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[200] flex items-center justify-center p-4 hidden">
        <div class="bg-[#111111] border border-[#333333] rounded-[3rem] w-full max-w-xl overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-[#333] flex justify-between items-center bg-[#1a1a1a]">
                <h3 class="text-xl font-black text-white">Nuevo Conjunto de Anuncio (Ad Set)</h3>
                <button onclick="window.closeModal('modal-adset')" class="text-[#a3a3a3] hover:text-white transition-colors"><i data-lucide="x"></i></button>
            </div>
            <form id="form-adset" class="p-8 space-y-6">
                <div>
                    <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Seleccionar Campaña</label>
                    <select id="adset-campaign" required class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold"></select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Nombre del Conjunto</label>
                    <input type="text" id="adset-name" required placeholder="Ej: Intereses Emprendedores Q2" class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Clasificación</label>
                        <select id="adset-classification" class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold"></select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Público / Segmentación</label>
                        <input type="text" id="adset-audience" placeholder="Ej: Lookalike 1%" class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#ff5100] text-white py-5 rounded-[2.5rem] font-black text-lg hover:bg-[#cc4200] transition-all">Crear Ad Set</button>
            </form>
        </div>
    </div>

    <div id="modal-ad" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[250] flex items-center justify-center p-4 hidden">
        <div class="bg-[#111111] border border-[#333333] rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-[#333] flex justify-between items-center bg-[#1a1a1a]">
                <h3 class="text-xl font-black text-white">Crear Anuncio</h3>
                <button onclick="window.closeModal('modal-ad')" class="text-[#a3a3a3] hover:text-white"><i data-lucide="x"></i></button>
            </div>
            <form id="form-ad" class="p-8 space-y-6">
                <input type="hidden" id="ad-adset-id">
                <div>
                    <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Nombre del Anuncio</label>
                    <input type="text" id="ad-name" required placeholder="Ej: Anuncio Gancho Emocional 01" class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-white font-bold">
                </div>
                <button type="submit" class="w-full bg-white text-black py-5 rounded-[2.5rem] font-black text-lg hover:bg-[#e0e0e0] transition-all">Añadir Anuncio</button>
            </form>
        </div>
    </div>

    <div id="modal-media" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[300] flex items-center justify-center p-4 hidden">
        <div class="bg-[#111111] border border-[#333333] rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-[#333] flex justify-between items-center bg-[#1a1a1a]">
                <h3 class="text-xl font-black text-white">Vincular Video / Gráfica</h3>
                <button onclick="window.closeModal('modal-media')" class="text-[#a3a3a3] hover:text-white"><i data-lucide="x"></i></button>
            </div>
            <form id="form-media" class="p-8 space-y-6">
                <input type="hidden" id="media-ad-id">
                <div>
                    <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">Nombre del Recurso</label>
                    <input type="text" id="media-name" required placeholder="Ej: Video 16:9 Hook A" class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-[#a3a3a3] ml-1">URL (Drive / Biblioteca Meta)</label>
                    <input type="url" id="media-url" required placeholder="https://..." class="w-full p-4 bg-[#000] border border-[#333] text-white rounded-2xl outline-none focus:border-[#ff5100] font-bold">
                </div>
                <button type="submit" class="w-full bg-[#ff5100] text-white py-5 rounded-[2.5rem] font-black text-lg hover:bg-[#cc4200] transition-all">Añadir Recurso</button>
            </form>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="fixed top-8 right-8 bg-[#111] border border-[#333] text-white px-6 py-4 rounded-2xl shadow-2xl transform translate-x-72 transition-transform duration-500 pointer-events-none flex items-center gap-3 z-[1000]">
        <div class="bg-[#ff5100] p-1 rounded-full"><i data-lucide="check" class="w-4 h-4 text-white"></i></div>
        <span id="toast-msg" class="font-bold text-sm tracking-tight text-white"></span>
    </div>
