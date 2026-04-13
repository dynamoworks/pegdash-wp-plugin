// --- APP.JS (VERSIÓN AJAX PHP NATIVA DE WORDPRESS) ---

const ajaxUrl = window.pegDashVars?.ajaxUrl || '';
const nonce = window.pegDashVars?.nonce || '';

// --- Estado Global ---
let data = { campaigns: [], adsets: [], ads: [], media: [], ad_logs: [], sales_logs: [], classifications: [], ticket_types: [] };
let activeCampaignId = "";
let charts = {};
let isReady = false;

// Configurar el Identificador de Sistema
const elAuth = document.getElementById('auth-status');
if (elAuth) elAuth.innerText = `DB: SQL Local (AJAX PHP)`;

// --- Helpers de Servidor PHP AJAX ---

async function fetchAjax(actionName, entity, payload = null, id = null) {
    const formData = new URLSearchParams();
    formData.append('action', actionName);
    formData.append('security', nonce);
    formData.append('entity', entity);
    if(payload) formData.append('payload', JSON.stringify(payload));
    if(id) formData.append('id', id);

    try {
        const response = await fetch(ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: formData.toString()
        });
        
        if (!response.ok) {
            alert(`🚨 Fallo en la comunicación con admin-ajax.php: ${response.status}`);
            return null;
        }

        const json = await response.json();
        if(json.success) {
            return json.data;
        } else {
            alert(`⚠️ Error PHP AJAX en Tabla '${entity}':\n${json.data}`);
            return null;
        }
    } catch(e) {
        console.error("AJAX Catch Error:", e);
        return null;
    }
}

async function loadAllData() {
    console.log("Sincronizando con Base de Datos SQL via admin-ajax...");
    
    if(window.Swal) Swal.fire({ title: 'Cargando Estadísticas...', text: 'Descargando datos', allowOutsideClick: false, background: '#111', color: '#fff', didOpen: () => Swal.showLoading() });

    // Obtenemos los arrays prometidos del backend
    const [c, as, ads, m, al, sl, cla, tt] = await Promise.all([
        fetchAjax('pegdash_get_data', 'campaigns'), fetchAjax('pegdash_get_data', 'adsets'), fetchAjax('pegdash_get_data', 'ads'),
        fetchAjax('pegdash_get_data', 'media'), fetchAjax('pegdash_get_data', 'ad_logs'), fetchAjax('pegdash_get_data', 'sales_logs'),
        fetchAjax('pegdash_get_data', 'classifications'), fetchAjax('pegdash_get_data', 'ticket_types')
    ]);
    
    if(window.Swal) Swal.close();
    
    data.campaigns = c || []; 
    data.adsets = as || []; 
    data.ads = ads || []; 
    data.media = m || [];
    data.ad_logs = al || []; 
    data.sales_logs = sl || []; 
    data.classifications = cla || []; 
    data.ticket_types = tt || [];

    if(!activeCampaignId && data.campaigns.length > 0) {
        activeCampaignId = data.campaigns[0].id;
    }
    
    isReady = true;
    render();
}

async function addDocSQL(entity, payload) {
    if(window.Swal) Swal.fire({ title: 'Guardando...', allowOutsideClick: false, background: '#111', color: '#ff5100', didOpen: () => Swal.showLoading() });
    
    const newDoc = await fetchAjax('pegdash_save_data', entity, payload);
    
    if(window.Swal) Swal.close();

    if(newDoc && newDoc.id) {
        data[entity].push(newDoc);
        render();
        return newDoc;
    }
    return null;
}

window.deleteDocById = async (entity, id) => { 
    if(confirm("¿Seguro de eliminar permanentemente?")) {
        if(window.Swal) Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, background: '#111', color: '#ff5100', didOpen: () => Swal.showLoading() });
        const res = await fetchAjax('pegdash_delete_data', entity, null, id);
        if(window.Swal) Swal.close();

        if (res && res.deleted) {
            data[entity] = data[entity].filter(d => d.id !== String(id));
            render();
            window.showToast("Dato eliminado permanentemente");
        } else {
            console.error('No se pudo borrar desde la base de datos local');
        }
    }
};

// --- Render Core ---
function render() {
    if (!isReady) return;
    renderSelectors();
    renderStats();
    renderHierarchy();
    renderConfig();
    renderCharts();
    if(window.lucide) window.lucide.createIcons();
}

function renderSelectors() {
    const campSel = document.getElementById('active-campaign-selector');
    if(campSel) campSel.innerHTML = data.campaigns.map(c => `<option class="bg-[#111] text-white" value="${c.id}" ${c.id === activeCampaignId ? 'selected' : ''}>${c.name}</option>`).join('') || '<option value="" class="bg-[#111] text-white">Crea una campaña</option>';
    
    const adsetCampSel = document.getElementById('adset-campaign');
    if(adsetCampSel) adsetCampSel.innerHTML = data.campaigns.map(c => `<option class="bg-[#111] text-white" value="${c.id}" ${c.id === activeCampaignId ? 'selected' : ''}>${c.name}</option>`).join('') || '<option value="" class="bg-[#111] text-white">Sin campañas</option>';

    const adsetEntrySel = document.getElementById('ads-adset');
    if(adsetEntrySel) {
        const activeAdsets = data.adsets.filter(a => String(a.campaignId) === String(activeCampaignId));
        adsetEntrySel.innerHTML = activeAdsets.map(a => `<option class="bg-[#111] text-white" value="${a.id}">${a.name}</option>`).join('') || '<option value="" class="bg-[#111] text-white">Sin Ad Sets</option>';
    }

    const adsetModalClassSel = document.getElementById('adset-classification');
    if(adsetModalClassSel) adsetModalClassSel.innerHTML = data.classifications.map(c => `<option class="bg-[#111] text-white" value="${c.id}">${c.name}</option>`).join('');

    const salesTypeSel = document.getElementById('sales-type');
    if(salesTypeSel) {
        if (data.ticket_types.length === 0) {
            salesTypeSel.innerHTML = '<option value="" class="bg-[#111] text-white">Ve a Config para crear tipos</option>';
        } else {
            salesTypeSel.innerHTML = data.ticket_types.map(t => `<option class="bg-[#111] text-white" value="${t.name}">${t.name}</option>`).join('');
        }
    }
}

window.changeCampaign = (id) => {
    activeCampaignId = id;
    render();
};

function renderStats() {
    const camp = data.campaigns.find(c => String(c.id) === String(activeCampaignId));
    const adsetIds = data.adsets.filter(a => String(a.campaignId) === String(activeCampaignId)).map(a => String(a.id));
    
    const adLogs = data.ad_logs.filter(l => adsetIds.includes(String(l.adsetId)));
    const saleLogs = data.sales_logs.filter(l => String(l.campaignId) === String(activeCampaignId));

    const spend = adLogs.reduce((s, l) => s + (parseFloat(l.spend) || 0), 0);
    const leads = adLogs.reduce((s, l) => s + (parseInt(l.leads) || 0), 0);
    const salesQty = saleLogs.reduce((s, l) => s + (parseInt(l.qty) || 0), 0);
    const goal = camp ? parseInt(camp.goal) : 0;
    const progress = goal > 0 ? (salesQty / goal) * 100 : 0;

    const safeSetText = (id, txt) => { const el = document.getElementById(id); if(el) el.innerText = txt; };
    safeSetText('kpi-spend', `$${spend.toLocaleString()}`);
    safeSetText('kpi-leads', leads.toLocaleString());
    safeSetText('kpi-sales', salesQty.toLocaleString());
    safeSetText('kpi-cpl', leads > 0 ? `$${(spend / leads).toFixed(2)}` : '$0.00');
    safeSetText('kpi-cpv', salesQty > 0 ? `$${(spend / salesQty).toFixed(2)}` : '$0.00');
    safeSetText('goal-percentage', `${Math.min(100, progress).toFixed(1)}%`);
    safeSetText('sold-count', `${salesQty} Entradas`);
    safeSetText('goal-text', `Meta: ${goal} Entradas`);

    const fill = document.getElementById('goal-progress-fill');
    if(fill) fill.style.width = `${Math.min(100, progress)}%`;

    const efficiencyList = document.getElementById('adset-efficiency-list');
    if(efficiencyList) {
        efficiencyList.innerHTML = data.adsets.filter(a => String(a.campaignId) === String(activeCampaignId)).map(a => {
            const aAdLogs = adLogs.filter(l => String(l.adsetId) === String(a.id));
            const aSpend = aAdLogs.reduce((s, l) => s + (parseFloat(l.spend) || 0), 0);
            const aLeads = aAdLogs.reduce((s, l) => s + (parseInt(l.leads) || 0), 0);
            const aCpl = aLeads > 0 ? (aSpend / aLeads).toFixed(2) : '-';
            return `
                <div class="flex justify-between items-center p-4 bg-[#1a1a1a] rounded-2xl border border-[#333333]">
                    <div>
                        <p class="text-xs font-black text-white">${a.name}</p>
                        <p class="text-[9px] font-bold text-[#a3a3a3] uppercase">$${aSpend.toFixed(2)} invert.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-white">${aLeads} Leads</p>
                        <p class="text-[9px] font-bold text-[#a3a3a3] uppercase">CPL: ${aCpl === '-' ? '-' : '$' + aCpl}</p>
                    </div>
                </div>
            `;
        }).join('') || '<p class="text-xs text-[#a3a3a3] italic text-center py-4">Sin datos</p>';
    }
}

function renderHierarchy() {
    const container = document.getElementById('hierarchy-container');
    if(!container) return;
    const activeAdsets = data.adsets.filter(a => String(a.campaignId) === String(activeCampaignId));
    container.innerHTML = data.classifications.map(cat => {
        const catAdsets = activeAdsets.filter(a => String(a.classificationId) === String(cat.id));
        if(catAdsets.length === 0) return '';
        return `
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 rounded-full" style="background-color: ${cat.color}"></div>
                    <h3 class="text-2xl font-black text-white">${cat.name}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    ${catAdsets.map(as => {
                        const adsetAds = data.ads.filter(ad => String(ad.adsetId) === String(as.id));
                        return `
                            <div class="bg-[#111111] rounded-[3rem] border border-[#222222] p-8 shadow-sm relative group overflow-hidden">
                                <button onclick="deleteDocById('adsets', '${as.id}')" class="absolute top-6 right-6 text-[#555] hover:text-[#ff5100] opacity-0 group-hover:opacity-100 transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <div class="mb-6">
                                    <span class="text-[9px] font-black uppercase text-[#ff5100] tracking-widest">${as.audience || 'Público'}</span>
                                    <h4 class="text-xl font-black text-white mt-1">${as.name}</h4>
                                </div>
                                <div class="space-y-4 mb-8">
                                    <p class="text-[10px] font-black uppercase text-[#a3a3a3] border-b border-[#333333] pb-1">Anuncios:</p>
                                    ${adsetAds.map(ad => {
                                        const adMedia = data.media.filter(m => String(m.adId) === String(ad.id));
                                        return `
                                            <div class="nested-card">
                                                <div class="flex justify-between items-start mb-2">
                                                    <p class="text-sm font-bold text-white leading-tight">${ad.name}</p>
                                                    <button onclick="deleteDocById('ads', '${ad.id}')" class="text-[#555] hover:text-[#ff5100]"><i data-lucide="x" class="w-3 h-3"></i></button>
                                                </div>
                                                <div class="space-y-1 mt-3">
                                                    ${adMedia.map(m => `
                                                        <div class="flex items-center justify-between bg-[#111111] px-3 py-2 rounded-xl border border-[#333] group/m">
                                                            <a href="${m.url}" target="_blank" class="text-[11px] font-bold text-[#a3a3a3] hover:text-[#ff5100] flex items-center gap-2 truncate transition-colors">
                                                                <i data-lucide="link" class="w-3 h-3"></i> ${m.name}
                                                            </a>
                                                            <button onclick="deleteDocById('media', '${m.id}')" class="text-[#555] hover:text-[#ff5100] opacity-0 group-hover/m:opacity-100 transition-all"><i data-lucide="minus" class="w-3 h-3"></i></button>
                                                        </div>
                                                    `).join('')}
                                                    <button onclick="openMediaModal('${ad.id}')" class="w-full mt-2 border-2 border-dashed border-[#333] py-2 rounded-xl text-[9px] font-black text-[#666] hover:border-[#ff5100] transition-all tracking-widest">+ Recurso</button>
                                                </div>
                                            </div>
                                        `;
                                    }).join('') || '<p class="text-[11px] text-[#555] italic">Sin anuncios</p>'}
                                </div>
                                <button onclick="openAdModal('${as.id}')" class="w-full bg-[#ff5100] text-white py-4 rounded-2xl text-[10px] font-black uppercase hover:bg-[#cc4200] transition-all gap-2 flex items-center justify-center">
                                    <i data-lucide="plus" class="w-3 h-3"></i> Nuevo
                                </button>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    }).join('') || '<div class="p-16 text-center"><p class="text-[#a3a3a3] font-bold">Crea tu primer Ad Set.</p></div>';
}

function renderConfig() {
    const campList = document.getElementById('campaign-list');
    if(campList) campList.innerHTML = data.campaigns.map(c => `
        <div class="flex justify-between items-center p-4 bg-[#1a1a1a] rounded-2xl border ${String(c.id) === String(activeCampaignId) ? 'border-[#ff5100]' : 'border-[#333]'}">
            <div>
                <p class="font-black text-white">${c.name}</p>
                <p class="text-[10px] text-[#a3a3a3]">Meta: ${c.goal}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="changeCampaign('${c.id}')" class="px-2 bg-transparent text-[10px] border border-[#444] rounded hover:bg-[#333]">Activar</button>
                <button onclick="deleteDocById('campaigns', '${c.id}')" class="text-[#555] hover:text-[#ff5100]"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
            </div>
        </div>
    `).join('');

    const classList = document.getElementById('class-list');
    if(classList) classList.innerHTML = data.classifications.map(c => `
        <div class="flex justify-between items-center p-3 bg-[#1a1a1a] border border-[#333] rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full" style="background-color: ${c.color}"></div>
                <span class="font-bold text-sm text-white">${c.name}</span>
            </div>
            <button onclick="deleteDocById('classifications', '${c.id}')" class="text-[#555] hover:text-[#ff5100]"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
        </div>
    `).join('');

    const ticketList = document.getElementById('ticket-type-list');
    if(ticketList) ticketList.innerHTML = data.ticket_types.map(t => `
        <div class="flex justify-between items-center p-3 bg-[#1a1a1a] border border-[#333] rounded-2xl">
            <span class="font-bold text-sm text-white">${t.name}</span>
            <button onclick="deleteDocById('ticket_types', '${t.id}')" class="text-[#555] hover:text-[#ff5100]"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
        </div>
    `).join('');
}

function renderCharts() {
    const canvas = document.getElementById('chartHistory');
    if(!canvas) return;
    const saleLogs = data.sales_logs.filter(l => String(l.campaignId) === String(activeCampaignId));
    
    const activeTypes = data.ticket_types.map(t => t.name);
    const logTypes = [...new Set(saleLogs.map(l => l.type))];
    const allTypes = [...new Set([...activeTypes, ...logTypes])];

    const daily = {};
    saleLogs.forEach(l => {
        if(!daily[l.date]) daily[l.date] = {};
        if (daily[l.date][l.type] === undefined) daily[l.date][l.type] = 0;
        daily[l.date][l.type] += (parseInt(l.qty) || 0);
    });

    const labels = Object.keys(daily).sort();
    if(charts.h) charts.h.destroy();

    if(window.Chart) Chart.defaults.color = '#a3a3a3';

    const palette = ['#ff5100', '#ffffff', '#888888', '#444444', '#ff854d', '#cccccc'];
    
    const datasets = allTypes.map((type, i) => {
        return {
            label: type,
            data: labels.map(date => daily[date][type] || 0),
            backgroundColor: palette[i % palette.length],
            borderRadius: 8
        };
    });

    charts.h = new Chart(canvas, {
        type: 'bar',
        data: { labels, datasets: datasets },
        options: { 
            responsive: true, 
            scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, beginAtZero: true, grid: { color: '#222' } } },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } }
        }
    });
}

window.switchTab = (tab) => {
    document.querySelectorAll('.app-section').forEach(s => s.classList.add('hidden'));
    document.getElementById(`section-${tab}`).classList.remove('hidden');
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('tab-active', 'text-white'));
    document.getElementById(`btn-${tab}`).classList.add('tab-active', 'text-white');
    if(window.lucide) window.lucide.createIcons();
};

window.openModal = (id) => document.getElementById(id).classList.remove('hidden');
window.closeModal = (id) => document.getElementById(id).classList.add('hidden');
window.openAdModal = (adsetId) => { document.getElementById('ad-adset-id').value = adsetId; openModal('modal-ad'); };
window.openMediaModal = (adId) => { document.getElementById('media-ad-id').value = adId; openModal('modal-media'); };

window.showToast = function(msg) {
    if (window.Swal) {
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: msg,
            showConfirmButton: false,
            timer: 3000,
            background: '#111111',
            color: '#ffffff',
            iconColor: '#ff5100'
        });
    } else {
        const t = document.getElementById('toast');
        const m = document.getElementById('toast-msg');
        if(t && m) { 
            m.innerText = msg; 
            t.classList.remove('translate-x-72'); 
            setTimeout(() => t.classList.add('translate-x-72'), 3000); 
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    
    // Iniciar carga de todo el Backend Local SQL
    loadAllData();
    
    document.querySelectorAll('input[type="date"]').forEach(d => d.valueAsDate = new Date());

    const attachSubmit = (id, fn) => { const el = document.getElementById(id); if(el) el.addEventListener('submit', fn); };

    attachSubmit('form-campaign', async (e) => {
        e.preventDefault();
        const payload = { name: document.getElementById('camp-name').value, goal: document.getElementById('camp-goal').value };
        const docRes = await addDocSQL('campaigns', payload);
        if(docRes) { activeCampaignId = docRes.id; }
        e.target.reset(); window.showToast("Campaña creada SQL");
    });

    attachSubmit('form-class', async (e) => {
        e.preventDefault();
        await addDocSQL('classifications', { name: document.getElementById('class-name').value, color: document.getElementById('class-color').value });
        e.target.reset(); window.showToast("Categoría añadida SQL");
    });

    attachSubmit('form-ticket-type', async (e) => {
        e.preventDefault();
        await addDocSQL('ticket_types', { name: document.getElementById('ticket-type-name').value });
        e.target.reset(); window.showToast("Tipo añadido");
    });

    attachSubmit('form-adset', async (e) => {
        e.preventDefault();
        await addDocSQL('adsets', {
            name: document.getElementById('adset-name').value,
            classificationId: document.getElementById('adset-classification').value,
            audience: document.getElementById('adset-audience').value,
            campaignId: document.getElementById('adset-campaign').value
        });
        e.target.reset(); window.closeModal('modal-adset'); window.showToast("Ad Set añadido");
    });

    attachSubmit('form-ad', async (e) => {
        e.preventDefault();
        await addDocSQL('ads', { name: document.getElementById('ad-name').value, adsetId: document.getElementById('ad-adset-id').value });
        e.target.reset(); window.closeModal('modal-ad'); window.showToast("Ad añadido");
    });

    attachSubmit('form-media', async (e) => {
        e.preventDefault();
        await addDocSQL('media', { name: document.getElementById('media-name').value, url: document.getElementById('media-url').value, adId: document.getElementById('media-ad-id').value });
        e.target.reset(); window.closeModal('modal-media'); window.showToast("Media SQL vinculada");
    });

    attachSubmit('form-entry-ads', async (e) => {
        e.preventDefault();
        await addDocSQL('ad_logs', {
            date: document.getElementById('ads-date').value,
            adsetId: document.getElementById('ads-adset').value,
            spend: parseFloat(document.getElementById('ads-spend').value),
            leads: parseInt(document.getElementById('ads-leads').value)
        });
        e.target.reset(); window.showToast("Gasto sincronizado SQL");
    });

    attachSubmit('form-entry-sales', async (e) => {
        e.preventDefault();
        await addDocSQL('sales_logs', {
            date: document.getElementById('sales-date').value,
            campaignId: activeCampaignId,
            type: document.getElementById('sales-type').value,
            qty: parseInt(document.getElementById('sales-qty').value),
            complimentaries: parseInt(document.getElementById('sales-complimentaries').value)
        });
        e.target.reset(); window.showToast("Ventas integradas SQL");
    });
});
