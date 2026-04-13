<div class="pegdash-wrapper h-screen bg-[#000000] flex overflow-hidden font-sans">
    
    <!-- LATERAL MENU (DESKTOP) & MOBILE SLIDE-IN -->
    <?php include PEGDASH_PLUGIN_DIR . '/views/partials/nav.php'; ?>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-0">
        <!-- TOP HEADER FOR QUICK ACTIONS -->
        <?php include PEGDASH_PLUGIN_DIR . '/views/partials/header.php'; ?>

        <!-- MAIN FLOW CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 space-y-8 bg-gradient-to-br from-[#0a0a0a] to-[#000]">
            <?php include PEGDASH_PLUGIN_DIR . '/views/partials/campaigns_hub.php'; ?>
            <?php include PEGDASH_PLUGIN_DIR . '/views/partials/stats.php'; ?>
            <?php include PEGDASH_PLUGIN_DIR . '/views/partials/entry.php'; ?>
            <?php include PEGDASH_PLUGIN_DIR . '/views/partials/structure.php'; ?>
            <?php include PEGDASH_PLUGIN_DIR . '/views/partials/config.php'; ?>
        </main>
    </div>
    
    <?php include PEGDASH_PLUGIN_DIR . '/views/partials/modals.php'; ?>
</div>
