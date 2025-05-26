jQuery(document).ready(function($) {
    // Initialize Select2
    $('#arsol-packet-filter').select2({
        placeholder: arsolScriptFilter.i18n.selectPacket,
        allowClear: true,
        width: '300px'
    }).on('change', function() {
        var packet = $(this).val();
        
        // Show loading state
        $('.arsol-addon-list').addClass('loading');
        
        // Filter scripts
        $.ajax({
            url: arsolScriptFilter.ajaxurl,
            type: 'POST',
            data: {
                action: 'arsol_filter_scripts',
                packet: packet,
                nonce: arsolScriptFilter.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateScriptDisplay(response.data);
                }
            },
            complete: function() {
                $('.arsol-addon-list').removeClass('loading');
            }
        });
    });

    // Function to update script display
    function updateScriptDisplay(filteredScripts) {
        // Hide all addon items first
        $('.arsol-addon-item').hide();
        
        // Show filtered addon items
        Object.keys(filteredScripts).forEach(function(scriptId) {
            $('.arsol-addon-item[data-addon-id="' + scriptId + '"]').show();
        });
    }
});
