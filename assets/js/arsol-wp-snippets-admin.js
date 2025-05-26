jQuery(document).ready(function($) {
    // Initialize Select2
    $('#arsol-packet-filter').select2({
        placeholder: arsolScriptFilter.i18n.selectPacket,
        allowClear: true,
        ajax: {
            url: arsolScriptFilter.ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    action: 'arsol_get_script_packets',
                    nonce: arsolScriptFilter.nonce
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item,
                            text: item
                        };
                    })
                };
            }
        }
    }).on('change', function() {
        var packet = $(this).val();
        
        // Show loading state
        $('.arsol-addon-container').addClass('loading');
        
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
                $('.arsol-addon-container').removeClass('loading');
            }
        });
    });

    // Function to update script display
    function updateScriptDisplay(filteredScripts) {
        // Hide all scripts first
        $('.arsol-addon-container').hide();
        
        // Show filtered scripts
        Object.keys(filteredScripts).forEach(function(scriptId) {
            $('#arsol-addon-' + scriptId).show();
        });
    }
});
