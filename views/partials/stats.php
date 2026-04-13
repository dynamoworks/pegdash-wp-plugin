        <div id="section-stats" class="app-section space-y-8">
            <div class="bg-[#111111] p-8 rounded-[2.5rem] border border-[#222222] shadow-sm relative">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-black text-white">Aprendizaje de Campaña</h3>
                        <p class="text-sm text-[#a3a3a3] font-medium">Progreso basado en la meta de entradas vendidas</p>
                    </div>
                    <div class="text-right">
                        <span id="goal-percentage" class="text-4xl font-black text-[#ff5100]">0%</span>
                    </div>
                </div>
                <div class="progress-bar mb-4">
                    <div id="goal-progress-fill" class="progress-fill" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-[11px] font-black uppercase tracking-widest text-[#a3a3a3]">
                    <span id="sold-count">0 Entradas</span>
                    <span id="goal-text">Meta: 0 Entradas</span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="card-kpi border-t-4 border-white border-l-[#222] border-r-[#222] border-b-[#222]">
                    <p class="text-[10px] font-black text-[#a3a3a3] uppercase tracking-widest mb-1">Gasto Ads</p>
                    <h3 id="kpi-spend" class="text-2xl font-black text-white">$0.00</h3>
                </div>
                <div class="card-kpi border-t-4 border-[#ff5100] border-l-[#222] border-r-[#222] border-b-[#222]">
                    <p class="text-[10px] font-black text-[#a3a3a3] uppercase tracking-widest mb-1">Costo por Lead</p>
                    <h3 id="kpi-cpl" class="text-2xl font-black text-[#ff5100]">$0.00</h3>
                </div>
                <div class="card-kpi border-t-4 border-white border-l-[#222] border-r-[#222] border-b-[#222]">
                    <p class="text-[10px] font-black text-[#a3a3a3] uppercase tracking-widest mb-1">Ventas Reales</p>
                    <h3 id="kpi-sales" class="text-2xl font-black text-white">0</h3>
                </div>
                <div class="card-kpi border-t-4 border-[#ff5100] border-l-[#222] border-r-[#222] border-b-[#222]">
                    <p class="text-[10px] font-black text-[#a3a3a3] uppercase tracking-widest mb-1">Costo por Venta</p>
                    <h3 id="kpi-cpv" class="text-2xl font-black text-[#ff5100]">$0.00</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-[#111111] p-8 rounded-[2.5rem] border border-[#222222]">
                    <h4 class="font-black text-white mb-6">Inscripciones por Día (Categorizadas)</h4>
                    <canvas id="chartHistory" height="150"></canvas>
                </div>
                <div class="bg-[#111111] p-8 rounded-[2.5rem] border border-[#222222]">
                    <h4 class="font-black text-white mb-4 text-sm uppercase tracking-wider">Rendimiento por Ad Set</h4>
                    <div id="adset-efficiency-list" class="space-y-4">
                        <!-- Listado de eficiencia -->
                    </div>
                </div>
            </div>
        </div>
