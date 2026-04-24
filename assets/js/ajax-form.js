(function (a) {
  "use strict";

  a("#contact-form").length &&
    (a("#contact-form").validator(),
    a("#contact-form").on("submit", function (b) {
      var c = a(this);
      if (!b.isDefaultPrevented())
        return (
          a.ajax({
            type: "POST",
            url: c.attr("action"),
            data: c.serialize(),
            dataType: "json",
            headers: { Accept: "application/json" },
            success: function (d) {
              var f = d && d.ok ? "alert-success" : "alert-danger";
              var g =
                d && d.message
                  ? d.message
                  : "There was an error while submitting the form. Please try again later.";
              var h =
                '<div class="alert ' +
                f +
                ' alert-dismissible" role="alert">' +
                g +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
              c.find(".messages").html(h);
              d && d.ok && c[0].reset();
            },
            error: function () {
              var d =
                '<div class="alert alert-danger alert-dismissible" role="alert">There was an error while submitting the form. Please try again later.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
              c.find(".messages").html(d);
            },
          }),
          !1
        );
    }));
})(jQuery);
