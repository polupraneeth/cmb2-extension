window.CMB2Ext = window.CMB2Ext || {};
(function (window, document, $, cmbExt, cmb, undefined) {
    'use strict';

    // localization strings
    //var l10n = window.cmb2_ext_l10;
    var setTimeout = window.setTimeout;
    var $document;
    var $id = function (selector) {
        return $(document.getElementById(selector));
    };
    cmbExt.$id = $id;
    var defaults = {};

    //console.log();
    cmbExt.init = function () {
        $document = $(document);
        
        // Setup the CMB2 Extenstion object defaults.
        $.extend( cmbExt, defaults );

        var $metabox = cmb.metabox();
        var $repeatGroup = $metabox.find('.cmb-repeatable-group');

        // Make List drag/drop sortable:
        cmbExt.makeListSortable();
        // Initialize ajax search
        cmbExt.initAjaxSearch();
        // Initialize font picker
        cmbExt.initFont();
        // Initialize slider
        cmbExt.initSlider();
        // Initialize Icon picker
        cmbExt.initIcon();
        // Initialize Map
        cmbExt.initMap();

        // Tabs Initialize
        $('.cmb-ext-tab-nav').on('click', '.cmb-ext-tab-nav-menu', cmbExt.toggleTabNav);

        // Initialize on widgets area
        $(document)
            .on('widget-updated widget-added', cmbExt.makeListSortable)
            .on('widget-updated widget-added', cmbExt.initAjaxSearch);

        $($metabox)
            .on('click', '.cmb2-ext-buttonset-label', cmbExt.toggleButtonSelect)
            .on('click', '.cmb-ext-content-wrap-field-switch .button', cmbExt.initContentWrap)
            .on('click', 'ul.cmb-image-select-list li input[type="radio"]', cmbExt.triggerImageSelect)
            .on('change.cmbAnimation', '.cmb-type-animation select', cmbExt.animateOnChange)
            .on('click', '.cmb-ext-ajax-search-results a.remover', cmbExt.ajax_search_remove_listener)
            .on('click.cmbAnimationPreview', '.cmb-type-animation .cmb-ext-animation-preview-button', cmbExt.animateOnClick)
            // Repeatable content
            .on('click', '.cmb-add-row-button', cmbExt.RepeatableFontIconDestroy);

        $('.cmb-repeat-table')
            .on('click', 'cmb2_add_row', cmbExt.RepeatableFontIconRefresh)
            .on('click', 'cmb2_add_row', cmbExt.RepeatableSliderRefresh)
            .on('click', 'cmb2_add_row', cmbExt.RepeatableMapRefresh);

        if ( $repeatGroup.length ) {
            $repeatGroup
                .on( 'cmb2_add_row', cmbExt.initAjaxSearchGroup )
                .on('cmb2_add_group_row_start', cmbExt.FontIconAddGroupDestroy)
                .on('cmb2_shift_rows_start', cmbExt.FontIconAddGroupShiftedDestroy)
                .on('cmb2_shift_rows_complete', cmbExt.FontIconAddGroupShiftedRefresh)
                .on('cmb2_shift_rows_complete', cmbExt.SliderAddGroupShiftedRefresh)
                .on( 'cmb2_add_row', cmbExt.FontIconAddGroupRefresh );

        }
    };
    /*--------------------------------------------------------------
    Tabs
    --------------------------------------------------------------*/
    cmbExt.toggleTabNav = function (e) {
        e.preventDefault();

        var $li = $(this).parent(),
            panel = $li.data('panel'),
            $wrapper = $li.parents(".cmb-tabs").find('.cmb-tabs-panel'),
            $panel = $wrapper.find('.cmb-tab-panel-' + panel);

        $li.addClass('cmb-tab-active').siblings().removeClass('cmb-tab-active');

        $panel.siblings().removeClass('show');
        $panel.each(function () {
            $(this).addClass("show");
        });
    };

    /*--------------------------------------------------------------
    Content Wrap
    --------------------------------------------------------------*/
    cmbExt.initContentWrap = function (e) {

        e.preventDefault();
        var wrap = $(this).closest('.cmb-ext-content-wrap');

        // Reset all values on change mode
        wrap.find('input').val('');

        if (wrap.hasClass('cmb-ext-content-wrap-multiple')) {
            wrap.removeClass('cmb-ext-content-wrap-multiple').addClass('cmb-ext-content-wrap-single');

            $(this).find('i').attr('class', 'dashicons dash','s-editor-expand');
        } else {
            wrap.removeClass('cmb-ext-content-wrap-single').addClass('cmb-ext-content-wrap-multiple');

            $(this).find('i').attr('class', 'dashicons dashicons-editor-contract');
        }
    };

    /*--------------------------------------------------------------
    Animate
    --------------------------------------------------------------*/
    // Animate on change
    cmbExt.animateOnChange = function () {
        var $this = $(this);
        var text = $this.closest('.cmb-ext-animation').find('.cmb-ext-animation-preview-text');

        if (text.length) {
            text.attr('class', 'cmb-ext-animation-preview-text animated');
            text.addClass($(this).val());
        }
    };

    // Animate on click
    cmbExt.animateOnClick = function () {
        var $this = $(this);
        var text = $this.next('.cmb-ext-animation-preview-text');
        var animation = text.attr('class').replace('cmb-ext-animation-preview-text', '');
        var select_val = $this.prev('select').val();

        // If no animation, then return
        if (animation === '' && select_val === '') {
            return false;
        }

        // If select has animation
        if (select_val !== '') {
            animation = select_val + ' animated';
        }

        text.attr('class', 'cmb-ext-animation-preview-text');

        setTimeout(function () {
            text.addClass(animation);
        }, 100);
    };

    /*--------------------------------------------------------------
    Order
    --------------------------------------------------------------*/
    cmbExt.makeListSortable = function () {
        var $list = cmb.$metabox.find('.sortable-list-ext');
        if ($list.length) {
            $list.sortable({
                handle: 'span',
                placeholder: 'ui-state-highlight',
                forcePlaceholderSize: true,
            }).disableSelection();
        }
    };

    /*--------------------------------------------------------------
    Image Select
    --------------------------------------------------------------*/
    cmbExt.triggerImageSelect = function (e) {
        e.stopPropagation(); // stop the click from bubbling
        var parent = $(this).closest('ul');
        $('.cmb-image-select-selected', parent).removeClass('cmb-image-select-selected');
        $(this).parent().closest('li').addClass('cmb-image-select-selected');
    };

    /*--------------------------------------------------------------
    Buttonset
    --------------------------------------------------------------*/
    cmbExt.toggleButtonSelect = function () {
        var parent = $(this).parents('.cmb2-ext-buttonset');
        $('.cmb2-ext-buttonset-label', parent).removeClass('selected');
        $(this).addClass('selected');
    };

    /*--------------------------------------------------------------
    Font
    --------------------------------------------------------------*/
    cmbExt.initFont = function () {
        
        $( '.cmb-type-font select' ).each(function() {
            $(this).higooglefonts({
                theme: 'default cmb-ext-font-select2',
                loadedCallback:function(font){
                    $(".font-preview").css("font-family", font);
                }
            });
        });

    };

    /*--------------------------------------------------------------
    Map
    --------------------------------------------------------------*/
    var maps = [];

    cmbExt.initMap = function () {

        $( '.cmb-type-map ' ).each(function() {
            cmbExt.singleMap( $(this) );
        });
    };

    // initialize map
    cmbExt.singleMap = function ( mapInstance ) {

        if ( typeof window.mapsCallback !== 'undefined' ){return;}

        var searchInput = mapInstance.find( '.cmb-map-search' );
        var mapCanvas = mapInstance.find( '.cmb-map' );
        var latitude = mapInstance.find( '.cmb-map-latitude' );
        var longitude = mapInstance.find( '.cmb-map-longitude' );
        var latLng = new google.maps.LatLng( 54.800685, -4.130859 );
        var zoom = 5;

        // If we have saved values, let's set the position and zoom level
        if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
            latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
            zoom = 17;
        }

        // Map
        var mapOptions = {
            center: latLng,
            zoom: zoom
        };
        var map = new google.maps.Map( mapCanvas[0], mapOptions );

        // Marker
        var markerOptions = {
            map: map,
            draggable: true,
            title: 'Drag to set the exact location'
        };
        var marker = new google.maps.Marker( markerOptions );

        if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
            marker.setPosition( latLng );
        }

        // Search
        var autocomplete = new google.maps.places.Autocomplete( searchInput[0] );
        autocomplete.bindTo( 'bounds', map );

        google.maps.event.addListener( autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if ( ! place.geometry ) {
                return;
            }

            if ( place.geometry.viewport ) {
                map.fitBounds( place.geometry.viewport );
            } else {
                map.setCenter( place.geometry.location );
                map.setZoom( 17 );
            }

            marker.setPosition( place.geometry.location );

            latitude.val( place.geometry.location.lat() );
            longitude.val( place.geometry.location.lng() );
        });

        $( searchInput ).keypress( function( event ) {
            if ( 13 === event.keyCode ) {
                event.preventDefault();
            }
        });

        // Allow marker to be repositioned
        google.maps.event.addListener( marker, 'drag', function() {
            latitude.val( marker.getPosition().lat() );
            longitude.val( marker.getPosition().lng() );
        });

        maps.push( map );
    };

    // Resize map when meta box is opened
    if ( typeof postboxes !== 'undefined' ) {
        postboxes.pbshow = function () {
            var arrayLength = maps.length;
            for (var i = 0; i < arrayLength; i++) {
                var mapCenter = maps[i].getCenter();
                google.maps.event.trigger(maps[i], 'resize');
                maps[i].setCenter(mapCenter);
            }
        };
    }

    // When a new row is added, reinitialize Google Maps
    cmbExt.RepeatableMapRefresh = function (event, newRow) {
        var groupWrap = $( newRow ).closest( '.cmb-repeatable-group' );
        groupWrap.find( '.cmb-type-map' ).each( function() {
            cmbExt.singleMap( $( this ) );
        });
    };

    /*--------------------------------------------------------------
    Slider
    --------------------------------------------------------------*/
    cmbExt.initSlider = function () {

        $( '.cmb-type-slider ' ).each(function() {
            cmbExt.slide( $(this) );
        });
    };

    // initialize slider row
    cmbExt.slide = function ( row ){

        // Loop through all cmb-type-slider-field instances and instantiate the slider UI
        row.each( function() {
            var $this       = $( this );
            var $value      = $this.find( '.cmb-slider-field-value' );
            var $slider     = $this.find( '.cmb-slider-field' );
            var $text       = $this.find( '.cmb-slider-field-value-text' );
            var slider_data = $value.data();

            $slider.slider({
                range : 'min',
                value : slider_data.start,
                min   : slider_data.min,
                max   : slider_data.max,
                step  : slider_data.step,
                slide : function( event, ui ) {
                    $value.val( ui.value );
                    $text.text( ui.value );
                }
            });

            // Initiate the display
            $value.val( $slider.slider( 'value' ) );
            $text.text( $slider.slider( 'value' ) );
        });
    };

    // When a group row is shifted, reinitialise slider value
    cmbExt.SliderAddGroupShiftedRefresh = function (event, instance) {
        var groupWrap = $(instance).closest('.cmb-repeatable-group');
        groupWrap.find('.cmb-type-slider').each(function () {
            $( this ).find( '.cmb-slider-field' ).slider( 'value', $(this).find( '.cmb-slider-field-value' ).val() );
            $( this ).find( '.cmb-slider-field-value-text' ).text( $(this).find( '.cmb-slider-field-value' ).val() );
        });
    };

    // When a group row is added, reset slider
    cmbExt.RepeatableSliderRefresh = function (event, newRow) {
        // Reinitialise the field we previously destroyed
        $(newRow).prev().find('.cmb-type-slider').each(function () {

            cmbExt.initSlider($(this));

            $( this ).find( '.ui-slider-range' ).css( 'width', 0 );
            $( this ).find( '.cmb-slider-field' ).slider( 'value', 0 );
            $( this ).find( '.cmb-slider-field-value-text' ).text( '0' );
        });
    };

    /*--------------------------------------------------------------
    Icon
    --------------------------------------------------------------*/
    cmbExt.initIcon = function () {
        $('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker({
                theme: 'fip-grey'
            });
        });
    };

    // Before a new repeatable field row is added, destroy Select2. We'll reinitialise after the row is added
    cmbExt.RepeatableFontIconDestroy = function (event) {
        var $table = $(document.getElementById($(event.target).data('selector')));
        var $oldRow = $table.find('.cmb-row').last();

        $oldRow.find('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker().destroyPicker();
        });
    };

    // When a new repeatable field row is added, clear selection and initialise Select2
    cmbExt.RepeatableFontIconRefresh = function (event, newRow) {
        // Reinitialise the field we previously destroyed
        $(newRow).prev().find('.cmb-ext-iconselect').each(function () {
            $('option:selected', this).removeAttr("selected");
            $(this).fontIconPicker().refreshPicker({
                theme: 'fip-grey'
            });
        });
    };

    // Before a new group row is added, destroy Select2. We'll reinitialise after the row is added
    cmbExt.FontIconAddGroupDestroy = function (event, instance) {
        var $table = $(document.getElementById($(instance).data('selector')));
        var $oldRow = $table.find('.cmb-repeatable-grouping').last();

        $oldRow.find('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker().destroyPicker();
        });
    };

    // Before a group row is shifted, destroy Select2. We'll reinitialise after the row shift
    cmbExt.FontIconAddGroupShiftedDestroy = function (event, instance) {
        var groupWrap = $(instance).closest('.cmb-repeatable-group');
        groupWrap.find('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker().destroyPicker();
        });
    };

    // When a new group row is added, clear selection and initialise Select2
    cmbExt.FontIconAddGroupRefresh = function (event, newRow) {
        $(newRow).find('.cmb-ext-iconselect').each(function () {
            $('option:selected', this).removeAttr("selected");
            $(this).fontIconPicker().refreshPicker({
                theme: 'fip-grey'
            });
        });

        // Reinitialise the field we previously destroyed
        $(newRow).prev().find('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker().refreshPicker({
                theme: 'fip-grey'
            });
        });
    };

    // When a group row is shifted, reinitialise Select2
    cmbExt.FontIconAddGroupShiftedRefresh = function (event, instance) {
        var groupWrap = $(instance).closest('.cmb-repeatable-group');
        groupWrap.find('.cmb-ext-iconselect').each(function () {
            $(this).fontIconPicker().refreshPicker({
                theme: 'fip-grey'
            });
        });
    };

    /*--------------------------------------------------------------
    Ajax Search
    --------------------------------------------------------------*/
    cmbExt.initAjaxSearch = function () {
        $('.cmb-ext-ajax-search:not([data-ajax-search="true"])').each(function () {
            $(this).attr('data-ajax-search', true);

            //var input_id = $(this).attr('id'); // Field id with '_input' sufix (the searchable field)
            var field_id = $(this).attr('id').replace(new RegExp('_input$'), ''); // Field id, the true one field
            var object_type = $(this).attr('data-object-type');
            var query_args = $(this).attr('data-query-args');

            $(this).devbridgeAutocomplete(Object.assign({
                    serviceUrl: cmb2_ext_l10.ajaxurl,
                    type: 'POST',
                    triggerSelectOnValidInput: false,
                    showNoSuggestionNotice: true,
                    params: {
                        action: 'cmb_ajax_search_get_results',
                        nonce: cmb2_ext_l10.nonce, // nonce
                        field_id: field_id,		// Field id for hook purposes
                        object_type: object_type, 	// post, user or term
                        query_args: query_args, 	// Query args passed to field
                    },
                    transformResult: function (results) {
                        var suggestions = $.parseJSON(results);

                        if ($('#' + field_id + '_results li').length) {
                            var selected_vals = [];
                            var d = 0;

                            $('#' + field_id + '_results input').each(function () {
                                selected_vals.push($(this).val());
                            });

                            // Remove already picked suggestions
                            $(suggestions).each(function (index, suggestion) {
                                if ($.inArray((suggestion.id).toString(), selected_vals) > -1) {
                                    suggestions.splice(index - d, 1);
                                    d++;
                                }
                            });
                        }

                        return {suggestions: suggestions};
                    },
                    onSearchStart: function () {
                        $(this).next('img.cmb-ext-ajax-search-spinner').css('display', 'inline-block');
                    },
                    onSearchComplete: function () {
                        $(this).next('img.cmb-ext-ajax-search-spinner').hide();
                    },
                    onSelect: function (suggestion) {
                        $(this).devbridgeAutocomplete('clearCache');

                        var field_name = $(this).attr('id').replace(new RegExp('_input$'), '');
                        var multiple = $(this).attr('data-multiple');
                        var limit = parseInt($(this).attr('data-limit'));
                        var sortable = $(this).attr('data-sortable');

                        if (multiple === 1) {
                            // Multiple
                            $('#' + field_name + '_results').append('<li>' +
                                ((sortable === 1) ? '<span class="hndl"></span>' : '') +
                                '<input type="hidden" name="' + field_name + '[]" value="' + suggestion.id + '">' +
                                '<a href="' + suggestion.link + '" target="_blank" class="edit-link">' + suggestion.value + '</a>' +
                                '<a class="remover"><span class="dashicons dashicons-no"></span><span class="dashicons dashicons-dismiss"></span></a>' +
                                '</li>');

                            $(this).val('');

                            // Checks if there is the max allowed results, limit < 0 means unlimited
                            if (limit > 0 && limit === $('#' + field_name + '_results li').length) {
                                $(this).prop('disabled', 'disabled');
                            } else {
                                $(this).focus();
                            }
                        } else {
                            // Singular
                            $('input[name=' + field_name + ']').val(suggestion.id).change();
                        }
                    }
                },
                cmb2_ext_l10.options));

            if ($(this).attr('data-sortable') === 1) {
                $('#' + field_id + '_results').sortable({
                    handle: '.hndl',
                    placeholder: 'ui-state-highlight',
                    forcePlaceholderSize: true
                });
            }
        });
    };

    // Initialize on group fields add row
    cmbExt.initAjaxSearchGroup = function (evt, $row) {
        $row.find('.cmb-ext-ajax-search').attr('data-ajax-search', false);
        cmbExt.initAjaxSearch();
    };

    // On click remover listener
    cmbExt.ajax_search_remove_listener = function () {
        $(this).parent('li').fadeOut(400, function () {
            var field_id = $(this).parents('ul').attr('id').replace('_results', '');

            $('#' + field_id).removeProp('disabled');
            $('#' + field_id).devbridgeAutocomplete('clearCache');

            $(this).remove();
        });
    };

    /*--------------------------------------------------------------
    Kick it off!
    --------------------------------------------------------------*/
    $(cmbExt.init);

})(window, document, jQuery, window.CMB2Ext, window.CMB2);
