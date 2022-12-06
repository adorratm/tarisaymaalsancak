window.addEventListener('DOMContentLoaded', function () {
    /*=====================
     24. Cookiebar
     ==========================*/
     if ($(".cookie-bar").length) {
        window.setTimeout(function () {
            $(".cookie-bar").addClass('show')
        }, 5000);

        $('.cookie-bar .btn, .cookie-bar .btn-close').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $(".cookie-bar").removeClass('show')
        });
    }
    // Preeloader 
    $(window).on("load", function () {
        $("#status").fadeOut();
        $("#preloader")
            .delay(350)
            .fadeOut("slow");
    });
    $(document).on("click", ".navbar-nav .dropdown-toggle", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $(this).parent().next().toggleClass("d-block");
    });

    // end if innerWidth
    $(document).on("click", ".btnSubmitForm", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        let $this = $(this);
        $this.attr("disabled", "disabled");
        createAjax($this.data("url"), new FormData(document.getElementById("contact-form")), function () {
            $("#contact-form")[0].reset();
            $this.removeAttr("disabled");
        }, function () {
            $this.removeAttr("disabled");
        });
    });
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"],[data-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});



function createAjax(e, t, n = function () { }, o = function () { }) {
    $.ajax({
        type: "POST",
        url: e,
        data: t,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "JSON"
    }).done(function (e) {
        e.success ? (iziToast.success({
            title: e.title,
            message: e.message,
            position: "topCenter",
            displayMode: "once"
        }), n(e), null !== e.redirect && "" !== e.redirect && void 0 !== e.redirect && setTimeout(function () {
            window.location.href = e.redirect
        }, 2e3)) : (iziToast.error({
            title: e.title,
            message: e.message,
            position: "topCenter",
            displayMode: "once"
        }), o(e), null !== e.redirect && "" !== e.redirect && void 0 !== e.redirect && setTimeout(function () {
            window.location.href = e.redirect
        }, 2e3))
    })
}

function createModal(e = null, t = null, n = null, o = 600, i = !0, l = "20px", r = 0, a = "#24b4a5", c = "#fff", u = 1040, s = function () { }, d = function () { }, p = function () { }, f = function () { }, g = function () { }, m = function () { }, h = function () { }, w = !0, b = !1, y = !0, v = !0, S = !1, k = 0) {
    "" === e && null === e || $(e).iziModal({
        title: t,
        subtitle: n,
        headerColor: a,
        background: c,
        width: o,
        zindex: u,
        fullscreen: w,
        openFullscreen: b,
        closeOnEscape: y,
        closeButton: v,
        overlayClose: S,
        autoOpen: k,
        padding: l,
        bodyOverflow: i,
        radius: r,
        onFullScreen: m,
        onResize: h,
        onOpening: s,
        onOpened: d,
        onClosing: p,
        onClosed: f,
        afterRender: g
    })
}

function openModal(e = null, t = function () { }) {
    $(e).iziModal("open", t)
}

function closeModal(e = null, t = function () { }) {
    $(e).iziModal("close", t)
}

function setCookie(e, t, n) {
    let o;
    if (n) {
        let e = new Date;
        e.setTime(e.getTime() + 24 * n * 60 * 60 * 1e3), o = "; expires=" + e.toGMTString()
    } else o = "";
    document.cookie = encodeURIComponent(e) + "=" + encodeURIComponent(t) + o + "; path=/"
}

function getCookie(e) {
    let t = encodeURIComponent(e) + "=",
        n = document.cookie.split(";");
    for (let e = 0; e < n.length; e++) {
        let o = n[e];
        for (;
            " " === o.charAt(0);) o = o.substring(1, o.length);
        if (0 === o.indexOf(t)) return decodeURIComponent(o.substring(t.length, o.length))
    }
    return null
}

function deleteCookie(e) {
    setCookie(e, "", -1)
}