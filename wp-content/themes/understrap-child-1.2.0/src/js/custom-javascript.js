// Add your custom JS here.
(function( $ ) {

    /* _________________________
* __________ AJAX ___________
____________________________*/

    function addParamToString(url, value) {
        if (url == '') {
            url = '?' + value.name + '=' + value.value;
        } else {
            url +='&' + value.name + '=' + value.value;
        }

        return url;
    }

    function collectParams(arrayData) {
        let urlParam = '';

        $.each(arrayData, function (index, value) {
            if (value.name != 'action' && value.value != '') {

                if( value.name == 'paged' && value.value != 1) {
                    value.name = '_s_paged';
                    urlParam = addParamToString(urlParam, value);
                } else if (value.name != 'paged') {
                    urlParam = addParamToString(urlParam, value);
                }
            }
        });

        return urlParam;
    }

    function addActiveFilters(arrayData) {
        $('.js_active_filters').html('');

        $.each(arrayData, function (index, value) {
            if (value.name != 'action' && value.name != 'paged' && value.value != '') {
                let style = 'border: 1px solid #e2e223; border-radius: 8px; padding: 4px 10px; margin: 8px; box-shadow: 0 0 10px rgba(246, 255, 50, .8)';
                $('.js_active_filters').append(`<span data-filter-slug="${value.value}" class="js_del_filter" style="${style}"> ${value.value}</span>`);
            }
        });

        if ($('.js_active_filters').children().length >= 2) {
            $('.js_active_filters').append(`<button class="js_clear_all">Clear all</button>`);
        }
    }

    var formFilter = $('.js-ajax-filter');

    formFilter.on('submit', function (e){
        e.preventDefault();

        let dataForm = $(this).serializeArray();

        addActiveFilters(dataForm);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: dataForm,
            success: function success(response) {
                if (response.data.found_posts > 0) {
                    $('.js_result').html(response.data.posts);
                } else {
                    $('.js_result').html('<p> NO DATA </p>');
                }
                formFilter.find('input[name="paged"]').val(1);

                let linkParam = collectParams(dataForm),
                    url = window.location.protocol + '//' + window.location.host + window.location.pathname + linkParam;

                window.history.pushState(null, 'my-ajax', url);
                window.history.replaceState(null, 'my-ajax', url);
            }
        });
    });

        formFilter.trigger('submit');

        $(document).on('click',function (e) {
            // console.log(e.target.nodeName);
            if ($(e.target).hasClass('pagination') && e.target.nodeName.toLowerCase() == 'a') {
                e.preventDefault();
                let el_page = $(e.target).text();

                if ($(e.target).hasClass('prev')) {
                    el_page = parseInt($('span.pagination.current').text()) - 1;
                } else if ($(e.target).hasClass('next')) {
                    el_page = parseInt($('span.pagination.current').text()) + 1;
                }
                formFilter.find('input[name="paged"]').val(el_page);
                formFilter.trigger('submit');
            }

            if ($(e.target).hasClass('js_del_filter') ) {
                let elementSlug = $(e.target).data('filter-slug');
                if($('.js-ajax-filter').find(`*[value="${elementSlug}"]`).prop('tagName').toLowerCase() === 'input') {
                    $(`input[value="${elementSlug}"]`).prop("checked", false);
                } else if ($('.js-ajax-filter').find(`*[value="${elementSlug}"]`).prop('tagName').toLowerCase() === 'option') {
                    $(`option[value="${elementSlug}"]`).parent().val('').trigger('change');
                }
                $(e.target).remove();
                formFilter.trigger('submit');
            }

            if ($(e.target).hasClass('js_clear_all')) {
                $('.js-ajax-filter').find('*input[type="checkbox"]').prop('checked', false);
                $('.js-ajax-filter select').val('').trigger('change');
                $(e.target).remove();
                formFilter.trigger('submit');
            }
        });

}( jQuery ));