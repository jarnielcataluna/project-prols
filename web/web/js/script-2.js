/**
 * Created by Hazel on 22/02/2017.
 */
// SCRIPT VARIABLES
const DOC = $(document);
const CURRENT_YEAR  = (new Date()).getFullYear();

var data, callback, done, always;

// LOADER VARIABLES
var loadingBar = $('.loader-notif');
var successBar = $('.successful-popup');
var errorBar = $('.error-popup');

function showLoadingBar() {
    loadingBar.css({'top': '0px'});
}

function hideLoadingBar() {
    loadingBar.css({'top': '-55px'});
}

function showSuccessBar(message) {
    successBar.find("#successful").html(message);
    successBar.css({'top': '0px'});
    setTimeout(function () {
        successBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 2000);
}

function showErrorBar(message) {
    console.log(message);
    errorBar.find("#error").html(message);
    errorBar.css({'top': '0px'});
    setTimeout(function () {
        errorBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 2000);
}

// AJAX POST
function post(button, path, data, callback, done, always) {
    showLoadingBar();
    var btnText = button.text();

    if(buttonIsReadyPlr(button)) {
        buttonLoadingPlr(button);

        $.post(path, data, callback, "json").done(function (data) {
            if (typeof done === 'function') done(data);
        }).always(function (data) {
            if (typeof always === 'function') always(data);

            buttonRemoveLoadPlr(button, btnText);
            hideLoadingBar();
        }).fail(function (data, err) {
            if (data.status == 401) {
                alert("You're not login!");
                window.location = '/';
            }

            showErrorBar("Server Error. Please try again.");
        });
    }
}

function buttonIsReadyPlr(btn) {
    return btn.hasClass('post-loading') ? false : true;
}

function buttonLoadingPlr(btn, text) {
    var btnText = text ? text : btn.data('loading-text');

    disableEnableButton(btn, true);
    btn.addClass('disabled').text(btnText||'Saving...');
}

function buttonRemoveLoadPlr(btn, text) {
    disableEnableButton(btn, false);
    btn.text(text||'Create');
}

function disableEnableButton(button, val) {
    if(val) button.addClass('disabled');
    else button.removeClass('disabled');

    button.prop("disabled", val);
}

// INPUT FIELD CHECKER
function checkFields(elements) {
    var hasRequired = 0;
    var element = null;
    for(var i = 0; i < elements.length; i++) {
        element = elements[i];
        if(element.val() == '') {
            notifyInvalid(element);
            hasRequired++;
        }
    }

    // return !hasRequired

    if (hasRequired > 0)
        return true;
    else return false;
}

function notifyInvalid(element) {
    element.css({'border-color': 'red'});
    element.focus();
    setTimeout(function () {
        element.css({'border-color': 'gray'})
    }, 1000);
}

function errorBorder(el) {
    for(var i in el) el[i].attr('style', 'border-color:red');
}

function defaultBorder(el) {
    for(var i in el) el[i].attr('style', 'border-color:gray');
}

function hideElements(els) {
    for( i in els) {
        els[i].hide();
    }
}
function showElements(els) {
    for( i in els) {
        els[i].show();
    }
}

function convertStatusToString(statusId) {
    if(statusId==STATUS_PENDING) {
        return 'pending';
    } else if(statusId==STATUS_ACTIVE) {
        return 'active';
    } else if(statusId==STATUS_INACTIVE) {
        return 'inactive';
    } else if(statusId==STATUS_APPROVED) {
        return 'approved';
    } else if(statusId==STATUS_DECLINED) {
        return 'declined';
    }
}

/** filters */
function removeSpace(str) {
    return str.replace(/\s/g, '');
}

/**
 * PLUGINS
 * ------------------------------
 */
/** init Date Picker */
var currentYear = (new Date()).getFullYear();
function initPicker(el, minDate, maxDate, onselect, param) {
    var pikael = 'pik_'+el;
    if(window[pikael] !== undefined && window[pikael].calendars !== undefined) {
        window[pikael].destroy();
    }

    window[pikael] = new Pikaday({
        field: document.getElementById(el),
        firstDay: 1,
        minDate: minDate,
        maxDate: maxDate,
        yearRange: [currentYear - 20, currentYear + 20],
        format: param && param.format ? param.format : 'YYYY-MM-DD',
        onSelect: onselect,
        showTime: param && param.showTime ? param.showTime : false,
        autoClose:  param && param.autoClose ? param.autoClose : true,
        use24hour: param && param.use24hour ? param.use24hour : false
    });

    return window[pikael];
}

function setPikadayData(info, el, value) {
    var pikael = 'pik_'+el;
    if(window[pikael] !== undefined && window[pikael].calendars !== undefined) {
        if(info=='end-range') window[pikael].setMaxDate(value);
        if(info=='start-range') window[pikael].setMinDate(value);
        if(info=='set-date') window[pikael].setDate(value);

        return window[pikael];
    }
}

function initDropDown(param) {
    param = param ? param: {};
    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: param.hover||false, // Activate on hover
            gutter: 0, // Spacing from edge
            belowOrigin: false, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
        }
    );
}