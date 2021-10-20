<div class="rsv-section rsv-section-step-3 ">
    <div class="rsv-tab">
        <div class="rsv-tab-header">
            <div class="tab-key active">Hébergement</div>
            <div class="tab-key">Vehicules</div>
            <div class="tab-key">Guides</div>
            <div class="tab-key">Equipements</div>
            <div class="tab-key">Discount</div>
            <div class="tab-key create-invoice">Invoice</div>
        </div>
        <div class="rsv-tab-body">
            <div class="tab-card card-accommodation active">
                <div class="rsv-next-tab">Next step</div>
                <?php print_mvr_products( 'hebergement' )?>
                <div class="rsv-next-tab">Next step</div>
            </div>
            <div class="tab-card card-vehicles">
                <div class="rsv-next-tab">Next step</div>
                <?php print_mvr_products( 'vehicules' )?>
                <div class="rsv-next-tab">Next step</div>
            </div>
            <div class="tab-card card-guides">
                <?php \mvr\Templates::print( 'tabs/guides' )?>
                <div class="rsv-next-tab">Next step</div>
            </div>
            <div class="tab-card card-equipements">
                <div class="rsv-next-tab">Next step</div>
                <?php print_mvr_products( 'equipements' )?>
                <div class="rsv-next-tab">Next step</div>
            </div>
            <div class="tab-card card-discount">
                <?php \mvr\Templates::print( 'tabs/discount' )?>
                <div class="rsv-next-tab create-invoice">Next step</div>
            </div>
            <div class="tab-card card-invoice"></div>
        </div>
    </div>
</div>