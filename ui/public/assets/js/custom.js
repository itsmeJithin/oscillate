(function ($) {
    $.fn.loading = function () {
        this.data("original", this.html());
        this.addClass("disabled");
        var loadingText = this.data("loading-text");
        if (!loadingText) loadingText = "Processing";
        var loadingHTML =
            '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;&nbsp;' + loadingText;
        this.html(loadingHTML);
        return this;
    };
    $.fn.resetLoading = function () {
        this.removeClass("disabled");
        this.html(this.data("original"));
    };
})(jQuery);

