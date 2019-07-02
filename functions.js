function getArgumentsBox(sentenceID, frameID) {
    tb_show("Annotate sentence", "getArgumentsBox.php?width=800&height=600&s=" + sentenceID + "&f=" + frameID);
    return false;
}

function saveTB(sentenceID, frameID) {
    var selects = new Array();
    var checkboxes = new Array();
    
    i = 0;
    jQuery(".tb_token_select").each(function() {
        if (jQuery(this).val() != 0) {
            var idtoken = jQuery(this).attr("id").replace(/[^0-9]/ig, "");
            selects[i++] = idtoken + "-" + jQuery(this).val();
        }
    });
    
    i = 0;
    jQuery(".tb_token_checkbox").each(function() {
        if (jQuery(this).is(":checked")) {
            var idtoken = jQuery(this).attr("id").replace(/[^0-9]/ig, "");
            checkboxes[i++] = idtoken;
        }
    });
    
    sel = selects.join();
    check = checkboxes.join();
    
    jQuery.ajax({
        url: 'saveData.php',
        data: {
            sel: sel,
            check: check,
            f: frameID,
            s: sentenceID
        },
        success: function() {
            jQuery("#sent_" + sentenceID).load("printP.php?s=" + sentenceID);
        }
    });
    
    // alert(sel);
    // alert(check);
    
    tb_remove();
}

function reloadColors() {
    jQuery(".div_token").each(function() {
        var v = jQuery(this).children("select").val();
        if (v == 0) {
            jQuery(this).children("span").css("background-color", "");
            jQuery(this).children("span").css("color", "");
        }
        else {
            jQuery(this).children("span").css("background-color", jQuery("#tb_tooltip_" + v).css("background-color"));
            jQuery(this).children("span").css("color", jQuery("#tb_tooltip_" + v).css("color"));
        }
        
        var c = jQuery(this).children("input").is(":checked");
        if (c) {
            jQuery(this).children("span").addClass("lu_keyword", "");
        }
        else {
            jQuery(this).children("span").removeClass("lu_keyword", "");
        }
    });
}

function argClicked(i, id) {
    lastArgClicked = id;
    reloadColors();
}

function arrowClicked(id) {
    if (lastArgClicked != null) {
        for (i = lastArgClicked + 1; i <= id; i++) {
            jQuery("#tb_token_" + i).val(jQuery("#tb_token_" + lastArgClicked).val());
        }
    }
    reloadColors();
    return false;
}

var lastArgClicked = null;

