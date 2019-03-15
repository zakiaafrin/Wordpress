/*

Sommary functions:


1 - Load Settings FEP
2 - Draggable Panel
3 - Collaspe Vertical Panel
4 - Collaspe Categories
5 - Draggable Categories
6 - Display / Hide switcher
7 - Collaspe Horizontal Panel
8 - Reset Panel

*/


/*--------------------------------------------------------------------------------------

1 - Load Settings FEP

--------------------------------------------------------------------------------------*/

// Load the settings FEP
function loadFepSettings() {
    //console.log(fepConfig); // for debugging

    //add exit icon
    if ( $("#fep-exit").length == 0 ) {
        exit_pannel = '<a id="fep-exit" target="_self" href="#" title="' + fep.exit_tooltip + '" class="fep-exit-link elementor-panel-footer-tool elementor-leave-open fep-tooltip"><i class="fa fa-sign-out"></i></a>';
        $("#elementor-panel-footer-saver-preview").after(exit_pannel);
    }

    //add collapse icon
    if ( $("#fep-collapse-vertical").length == 0 ) {
        collapse_vertical_pannel = '<div id="fep-collapse-vertical"><i class="fa fa-arrows-v fep-toggle-panel-icon"></i></div>';
        $("#elementor-panel-header-menu-button").after(collapse_vertical_pannel);
    }

    //add reset panel icon
    if ( $("#fep-reset-panel").length == 0 ) {
        fep_reset_pannel = '<div id="fep-reset-panel" class="elementor-header-button reset-fep"><i class="fa fa-arrows-alt tooltip-target"></i></div>';
        $("#elementor-panel-header-title").after(fep_reset_pannel);
    }

    if (fepConfig.draggable_panel == 'yes') {
        $("#elementor-panel").draggable("enable");
        $("#elementor-panel-header-title").on('touchstart mousedown', mousedownHeaderTitle);
        $("#elementor-panel-header-title").on('touchend mouseup', mouseupHeaderTitle);
        $("#elementor-panel-header-title").css("cursor", "move"); // add cursor to the title editor panel
    }
    if (fepConfig.draggable_panel == 'no') {
        $("#elementor-panel").draggable("disable");
        $("#elementor-panel-header-title").off('touchstart mousedown', mousedownHeaderTitle);
        $("#elementor-panel-header-title").off('touchend mouseup', mouseupHeaderTitle);
        $("#elementor-panel-header-title").css("cursor", ""); // remove special cursor to the title editor panel
    }
    if (fepConfig.minimize_category_space == 'yes') {
        $('body').addClass("fep-minimize-category");
    }
    if (fepConfig.minimize_category_space == 'no') {
        $('body').removeClass("fep-minimize-category");
    }
    if (fepConfig.use_grid_ruler == 'yes') {
        $('body').addClass("fep-elementor-grid-ruler");
    }
    if (fepConfig.use_grid_ruler == 'no') {
        $('body').removeClass("fep-elementor-grid-ruler");
    }
    if (fepConfig.editor_skin == 'dark_pink') {
        $('body').removeClass("nightmode nightmode-orange");
        $('body').addClass("nightmode nightmode-pink");
    }
    if (fepConfig.editor_skin == 'dark_orange') {
        $('body').removeClass("nightmode nightmode-pink");
        $('body').addClass("nightmode nightmode-orange");
    }
    if (fepConfig.editor_skin == 'light') {
        $('body').removeClass("nightmode nightmode-pink nightmode-orange");
    }
    if (fepConfig.dashboard_link_new_tab == 'yes') {
        $(".fep-exit-link").attr("target", "_blank");
    }
    if (fepConfig.dashboard_link_new_tab == 'no') {
        $(".fep-exit-link").attr("target", "_self");
    }
    if (fepConfig.dashboard_link_point == 'page_front') {
        $(".fep-exit-link").attr("href", PageFront.Permalink);
    }
    if (fepConfig.dashboard_link_point == 'page_edit') {
        $(".fep-exit-link").attr("href", window.location.href.replace("&action=elementor", "&action=edit"));
    }
    if (fepConfig.dashboard_link_point == 'page_list') {
        $(".fep-exit-link").attr("href", window.location.href.split('wp-admin')[0] + 'wp-admin/edit.php?post_type=page');
    }
    if (fepConfig.dashboard_link_point == 'elementor_library') {
        $(".fep-exit-link").attr("href", window.location.href.split('wp-admin')[0] + 'wp-admin/edit.php?post_type=elementor_library');
    }
    if (fepConfig.dashboard_link_point == 'admin_dashboard') {
        $(".fep-exit-link").attr("href", window.location.href.split('wp-admin')[0] + 'wp-admin/');
    }
    if (fepConfig.accordion_options == 'yes') {
        $("#elementor-preview-iframe").contents().find('.elementor-accordion .elementor-tab-title').first().removeClass('elementor-active');
        $("#elementor-preview-iframe").contents().find('.elementor-accordion .elementor-tab-content').first().css('display', 'none').removeClass('elementor-active');
    } else {
        $("#elementor-preview-iframe").contents().find('.elementor-accordion .elementor-tab-title').first().addClass('elementor-active');
        $("#elementor-preview-iframe").contents().find('.elementor-accordion .elementor-tab-content').first().css('display', 'block').removeClass('elementor-active');
    }
}



/*--------------------------------------------------------------------------------------

2 - Draggable Panel

--------------------------------------------------------------------------------------*/
// Make Elementor Panel draggable
function draggablePanel() {

    var panel_size_width = localStorage.getItem('elementor-panel-size-width');
    var panel_size_height = localStorage.getItem('elementor-panel-size-height');

    var panel_pos_top = localStorage.getItem('elementor-panel-pos-top');
    var panel_pos_left = localStorage.getItem('elementor-panel-pos-left');

    var in_move = localStorage.getItem('in-move');


    //console.log( in_move );
    //console.log(panel_pos_top + '-' + panel_pos_left);

    $("#elementor-panel").resizable({
        minWidth: 280,
        minHeight: 360,
        resize: function(event, ui) {
            event.preventDefault();

            $("#elementor-preview").css("pointer-events", "none"); // disable pointer on the preview elementor

            //if panel is in not in move
            if ( ! $("#elementor-panel").hasClass("in-move") ) {
                panelWidth = $("#elementor-panel").width();
                $("#elementor-preview").css( 'left', panelWidth ); // set
            }

        },
        stop: function(event, ui) {
            event.preventDefault();

            $("#elementor-preview").css("pointer-events", ""); // active pointer on the preview elementor

            panelWidth = $("#elementor-panel").width();
            panelHeight = $("#elementor-panel").height();

            //if panel is in not in move
            if ( ! $("#elementor-panel").hasClass("in-move") ) {
                $("#elementor-preview").css( 'left', panelWidth ); // set
            }

            localStorage.setItem('elementor-panel-size-width', panelWidth); //save
            localStorage.setItem('elementor-panel-size-height', panelHeight);

        }
    });

    //console.log( panel_size_height + '-' + $(window).height() ); // for debugging


    // do it, if fep is already drag
    if ( in_move === '1' ) {

        //console.log('do it');

        // check if the panel is oversize of windows height and panel more up of top windows
        if ( panel_size_height >= $(window).height() || panel_pos_top < 0 ) {

            $("#elementor-panel").css({
                'top': 0,
                'left': panel_pos_left + 'px'
            }); // move the panel at the save position but force top 0
            $("#elementor-panel").css("height", $(window).height() - parseInt($("#elementor-panel").css("top"))); // remove the force height

            // set var panel size with special height
            panel_size = {
                width: panel_size_width + 'px',
                height: $(window).height() + 'px'
            };

        } else {

            $("#elementor-panel").css({
                'top': panel_pos_top + 'px',
                'left': panel_pos_left + 'px'
            }); // move the panel at the save position

            // set var panel size
            panel_size = {
                width: panel_size_width + 'px',
                height: panel_size_height + 'px'
            };

        }


        $('#elementor-preview').css('left', 0); // full preview size when panel is in drag to load

        $("#elementor-panel").addClass("in-move"); // add class to panel for say he is in drag

        $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-e"); // remove resizable the right side of panel editor
        $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-all"); // add resizable all panel editor

        elementor_switcher_display_none(); // hide the switcher

        $("#elementor-panel").css(panel_size); //set the height and width of panel editor

    } else {

        //if size exist
        if ( panel_size_width ) {
            $("#elementor-panel").css( 'width', panel_size_width + 'px' ); //set the width of panel editor
        }
        $('#elementor-preview').animate({
                                            'left': panel_size_width  + 'px',
                                        }, 150);

    }


    // draggable panel !! al right ;)
    $("#elementor-panel").draggable({
        handle: "#elementor-panel-header-title",
        snap: "#elementor-preview",
        opacity: 0.7,
        cancel: ".not-draggable",
        addClasses: false,
        containment: "window",
        snapMode: "inner",
        snapTolerance: 25,
        start: function() {

            $('#elementor-preview').animate({
                                                'left': 0,
                                            }, 150);



        },
        stop: function(event, ui) {

            // add class "in-move" when panel is in move
            $("#elementor-panel").addClass("in-move");

            localStorage.setItem('in-move', 1);

            localStorage.setItem('elementor-panel-pos-top', ui.position.top);
            localStorage.setItem('elementor-panel-pos-left', ui.position.left);

        }
    });

    //$("#elementor-panel-content-wrapper").addClass("not-draggable"); // add class for disable the draggable at wrapper area
    //$("#elementor-panel-footer").addClass("not-draggable"); // add class for disable the draggable at footer area
    //$("#elementor-mode-switcher").addClass("not-draggable"); // add class for disable the draggable at mode switcher area

}


// MOUSEDOWN (at the click mouse)
function mousedownHeaderTitle() {

    $("#elementor-preview").css("pointer-events", "none"); // disable pointer on the preview elementor

    // when start to draggable, do it if the panel still in corner top left
    if ( $('#elementor-panel').css('left') === '0px' && $('#elementor-panel').css('top') === '0px' ) {

        $("#elementor-panel").css("height", "65%"); // set panel height 65% of windows
        $("#elementor-panel-content-wrapper").slideDown(150); // transition
        $("#elementor-panel-footer").slideDown(150);// transition

        $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-e"); // remove resizable the right side of panel editor
        $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-all"); // add resizable all panel editor

        elementor_switcher_display_none(); // remove switcher preview mode

        // clean height size for exclude conflict with vertical callapse
        var panelHeight = $("#elementor-panel").height(); // get
        localStorage.setItem('elementor-panel-size-height', panelHeight); //save
        $('#elementor-panel').removeClass('vertical_elementor_panel_toggle-on'); // collapse is off              

    }

}

// MOUSEUP (at leave the click mouse)
function mouseupHeaderTitle() {

    $("#elementor-preview").css("pointer-events", ""); // active pointer on the preview elementor

    // reset position panel to origin when click on title if he is on corner top left
    if ( $('#elementor-panel').css('left') === '0px' && $('#elementor-panel').css('top') === '0px' ) {

        $("#elementor-panel").css("height", $(window).height() + 'px'); // resize full height panel

        $("#elementor-panel-content-wrapper").slideDown(150); // transiton
        $("#elementor-panel-footer").slideDown(150);// transiton

        $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-e"); // add resizable the right side of panel editor
        $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-all"); // remove resizable all panel editor

        elementor_switcher_display_block(); // show button resize preview

        // remove class "in-move" when panel back in origine position
        $("#elementor-panel").removeClass("in-move");
        localStorage.setItem('in-move', 0);

        // replace the preview
        panelWidth = $("#elementor-panel").width(); // get size panel
        $('#elementor-preview').animate({
                                            'left': panelWidth + 'px',
                                        }, 150);

    }

}

/*--------------------------------------------------------------------------------------

3 - Collaspe Vertical Panel

--------------------------------------------------------------------------------------*/
// FUNCTION VERTICAL COLLAPSE/EXPAND ELEMENTOR PANEL
function vertical_elementor_panel_toggle() {

    // if has class vertical_elementor_panel_toggle-on, dont resize
    if ( $('#elementor-panel').hasClass('vertical_elementor_panel_toggle-on') ) {

        $("#elementor-preview-iframe").contents().find("body").removeClass('elementor-editor-preview').addClass('elementor-editor-active'); // disable preview
        $("body").removeClass('elementor-editor-preview').addClass('elementor-editor-active'); // disable preview

        $('#elementor-panel').removeClass('vertical_elementor_panel_toggle-on'); // Important, remove class to panel for understand the collapse is off

        // if panel is not in drag
        if ( !$('#elementor-panel').hasClass('in-move') ) {

            //console.log('left top corner and open');

            $("#elementor-panel").animate({
                height: $(window).height() + 'px'
            }, 0); // add the height px minus the top px

            $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-e"); // add resizable the right side of panel editor
            $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-all"); // remove resizable all panel editor

            elementor_switcher_display_block(); // show switcher

            // set normal preview with panel
            panelWidth = $("#elementor-panel").width(); // get size panel
            $('#elementor-preview').animate({
                                                'left': panelWidth + 'px',
                                            }, 150);

        } else {

            $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-e"); // add resizable the right side of panel editor
            $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-all"); // remove resizable all panel editor

            var panel_size_height = localStorage.getItem('elementor-panel-size-height');

            if ( panel_size_height >= parseInt($("#elementor-panel").css("top")) ) {
                $("#elementor-panel").animate({
                    height: panel_size_height
                }, 150); // add the height px minus the top px
            } else {
                $("#elementor-panel").animate({
                    height: $(window).height() - parseInt($("#elementor-panel").css("top"))
                }, 150); // add the height px minus the top px
            }

        }

        //alert('resize off');

    } else {

        //save height
        var panelHeight = $("#elementor-panel").height();
        localStorage.setItem('elementor-panel-size-height', panelHeight);

        $("#elementor-preview-iframe").contents().find("body").addClass('elementor-editor-preview').removeClass('elementor-editor-active'); // active preview
        $("body").addClass('elementor-editor-preview').removeClass('elementor-editor-active'); // active preview

        $('#elementor-panel').addClass('vertical_elementor_panel_toggle-on'); // Important, add class to panel for understand the collapse is on


        //get left position
        //var position = $( "#elementor-panel" ).position();
        $("#elementor-panel").animate({
            height: "40px",
            //left: position.left,
        }, 150); // resize the panel to height 40px

        $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-all"); // remove resizable all panel editor

        elementor_switcher_display_none(); // remove swicther

        // set full preview
        $('#elementor-preview').animate({
                                            'left': 0,
                                        }, 150);

        //alert('resize on');

    }

}

/*--------------------------------------------------------------------------------------

4 - Collaspe Categories

--------------------------------------------------------------------------------------*/
// Close all categories in panel with the right click
function collapseCategories(delay) {

    if (delay) {
        delay = 0; // remove transition
    } else {
        delay = 280; // add delay for smoothing
    }

    //alert(localStorage.getItem("cat-closed")); // for debugging

    // remove window click right chrome
    $("#elementor-panel-elements-categories").on("contextmenu", function() {
        return false;
    });

    $('#elementor-panel-elements-categories').mousedown(function(event) {

        if (event.which == 3) { // right click

            if (localStorage.getItem('cat-closed') == 0) {

                $(".elementor-panel-category-items").slideUp(280);
                $(".elementor-panel-category").removeClass("elementor-active");
                $('.elementor-panel-category-items')
                    .delay(280)
                    .queue(function(next) {
                        $(this).css('display', 'none');
                        next();
                    });

                localStorage.setItem('cat-closed', '1');
                //console.log(localStorage.getItem("cat-closed")); // for debugging

            } else {
                $(".elementor-panel-category").addClass("elementor-active");
                $(".elementor-panel-category-items").slideDown(280);
                $(".elementor-panel-category-items").css("display", "block");

                localStorage.setItem('cat-closed', '0');
                //console.log(localStorage.getItem("cat-closed")); // for debugging
            }
        }
    });


    // load conditionnal if the save collapse is "closed"
    if (localStorage.getItem('cat-closed') == 1) {
        $(".elementor-panel-category-items").slideUp(delay);
        $(".elementor-panel-category").removeClass("elementor-active");
        $('.elementor-panel-category-items')
            .delay(delay)
            .queue(function(next) {
                $(this).css('display', 'none');
                next();
            });
    }

}


/*--------------------------------------------------------------------------------------

5 - Draggable Categories

--------------------------------------------------------------------------------------*/
// Make Categories in elementor panel draggable
function draggableCategories() {

    $("#elementor-panel-categories").sortable({
        cursor: "move",
        axis: "y",
        opacity: 0.5,
        cancel: ".elementor-element-wrapper",
        create: createPositionsCategories,
        update: refreshPositionsCategories
    });

}

function refreshPositionsCategories() {

    // refresh tabindex number by order categorie
    $('.elementor-panel-category').each(function(i) {

        $(this).attr('tabindex', i + 1); // reload tabindex to all single category

        var idCategory = this.id; // get id
        var tabindexCategory = $(this).attr('tabindex'); // get tabindex

        var i = i + 1; // add first number 1

        localStorage.setItem('cat-position-' + tabindexCategory, idCategory); // save position

    });

}

function createPositionsCategories() {

    // did action by number of category
    $(".elementor-panel-category").each(function(i) {
        var i = i + 1;

        if (localStorage.getItem('cat-position-' + i)) {
            $("#" + localStorage.getItem('cat-position-' + i)).appendTo("#elementor-panel-categories"); // clear and put the new order with appendTo
        }
    });

}


/*--------------------------------------------------------------------------------------

6 - Display / Hide switcher

--------------------------------------------------------------------------------------*/

// Show the arrow of switcher elementor editor
function elementor_switcher_display_block() {
    $("#elementor-mode-switcher").css("display", "block");
}
// Hide the arrow of switcher elementor editor
function elementor_switcher_display_none() {
    $("#elementor-mode-switcher").css("display", "none");
}


/*--------------------------------------------------------------------------------------

7 - Collaspe Horizontal Panel

--------------------------------------------------------------------------------------*/
function elementor_horizontal_panel() {

    switcher_checkbox = $( '#elementor-mode-switcher-preview-input' ).is(':checked'); // get value checkbox
    //console.log( switcher_checkbox );

    panelWidth = $("#elementor-panel").width(); // get size panel

    //elementor-editor-active = panel open
    //elementor-editor-preview = panel close

    // false = panel open
    if ( switcher_checkbox == false ) {

        $('#elementor-preview').animate({
                                            'left': 0,
                                        }, 150);

        $('#elementor-panel').animate({
                                            'left': '-' + panelWidth + 'px',
                                        }, 150);

        $('#elementor-mode-switcher-preview-input').prop('checked', true);

        $("#elementor-preview-iframe").contents().find("body").addClass('elementor-editor-preview').removeClass('elementor-editor-active'); // active preview
        $("body").addClass('elementor-editor-preview').removeClass('elementor-editor-active'); // active preview

    } else {

        $('#elementor-preview').animate({
                                            'left': panelWidth + 'px',
                                        }, 150);
        $('#elementor-panel').animate({
                                            'left': 0,
                                        }, 150);


        $('#elementor-mode-switcher-preview-input').prop('checked', false);

        $("#elementor-preview-iframe").contents().find("body").removeClass('elementor-editor-preview').addClass('elementor-editor-active'); // disable preview
        $("body").removeClass('elementor-editor-preview').addClass('elementor-editor-active'); // disable preview

    }

}


/*--------------------------------------------------------------------------------------

8 - Reset Panel

--------------------------------------------------------------------------------------*/
function reset_fep_panel() {

    //console.log('ok');

    $("#elementor-panel").removeAttr('style'); // remove
    $(".elementor-panel > .ui-resizable-handle").addClass("ui-resizable-e"); // add resizable the right side of panel editor
    $(".elementor-panel > .ui-resizable-handle").removeClass("ui-resizable-all"); // remove resizable all panel editor

    elementor_switcher_display_block(); // show button resize preview

    // remove class "in-move" when panel back in origine position
    $("#elementor-panel").removeClass("in-move"); // remove
    localStorage.setItem('in-move', 0);//save

    // replace the preview
    panelWidth = $("#elementor-panel").width(); // get size panel
    $('#elementor-preview').animate({
                                        'left': panelWidth + 'px',
                                    }, 150);

    var panelWidth = $("#elementor-panel").width(); // get
    var panelHeight = $("#elementor-panel").height(); // get

    localStorage.setItem('elementor-panel-size-width', panelWidth); //save
    localStorage.setItem('elementor-panel-size-height', panelHeight); //save

    var position = $( "#elementor-panel" ).position(); // get

    localStorage.setItem('elementor-panel-pos-top', position.top); //save
    localStorage.setItem('elementor-panel-pos-left', position.left); //save

}
