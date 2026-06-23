(function( $ ) {

    function addParamToString(url, value) {
        if (url === '') {
            return '?' + value.name + '=' + value.value;
        }
        return url + '&' + value.name + '=' + value.value;
    }

    function collectParams(arrayData) {
        let urlParam = '';

        $.each(arrayData, function (index, value) {
            if (value.name !== 'action' && value.value !== '') {
                if (value.name === 'paged' && value.value !== '1') {
                    value.name = '_s_paged';
                    urlParam = addParamToString(urlParam, value);
                } else if (value.name !== 'paged' && value.name !== 'posts_per_page') {
                    urlParam = addParamToString(urlParam, value);
                }
            }
        });

        return urlParam;
    }

    function addActiveFilters($form, arrayData) {
        var $container = $form.closest('.re-catalog').find('.js_active_filters');
        $container.html('');

        $.each(arrayData, function (index, value) {
            if (value.name !== 'action' && value.name !== 'paged' && value.name !== 'posts_per_page' && value.value !== '') {
                $container.append(
                    '<span data-filter-slug="' + value.value + '" class="js_del_filter re-active-filters__chip">' + value.value + '</span>'
                );
            }
        });

        if ($container.children('.js_del_filter').length >= 2) {
            $container.append('<button type="button" class="js_clear_all re-active-filters__clear">Clear all</button>');
        }
    }

    function updateResultCount($catalog, count) {
        var $el = $catalog.find('.js_result-count');
        if (!$el.length) {
            return;
        }
        if (count > 0) {
            $el.text(count + ' ' + (count === 1 ? 'property' : 'properties'));
        } else {
            $el.text('');
        }
    }

    var formFilter = $('.js-ajax-filter');

    if (!formFilter.length) {
        return;
    }

    formFilter.on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $catalog = $form.closest('.re-catalog');
        var dataForm = $form.serializeArray();

        addActiveFilters($form, dataForm);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: dataForm,
            success: function (response) {
                if (response.data.found_posts > 0) {
                    $catalog.find('.js_result').html(response.data.posts);
                } else {
                    $catalog.find('.js_result').html(response.data.posts || '<div class="re-empty"><h3 class="re-empty__title">No properties found</h3><p class="re-empty__text">Try adjusting your filters.</p></div>');
                }
                updateResultCount($catalog, response.data.found_posts);
                $form.find('input[name="paged"]').val(1);

                var linkParam = collectParams(dataForm),
                    url = window.location.protocol + '//' + window.location.host + window.location.pathname + linkParam;

                window.history.pushState(null, 'my-ajax', url);
                window.history.replaceState(null, 'my-ajax', url);
            }
        });
    });

    formFilter.each(function () {
        $(this).trigger('submit');
    });

    $(document).on('click', function (e) {
        var $target = $(e.target);

        if ($target.hasClass('pagination') && e.target.nodeName.toLowerCase() === 'a') {
            e.preventDefault();

            var $catalog = $target.closest('.re-catalog');
            var $form = $catalog.find('.js-ajax-filter');
            var el_page = $target.text();

            if ($target.hasClass('prev')) {
                el_page = parseInt($catalog.find('span.pagination.current').text(), 10) - 1;
            } else if ($target.hasClass('next')) {
                el_page = parseInt($catalog.find('span.pagination.current').text(), 10) + 1;
            }

            $form.find('input[name="paged"]').val(el_page);
            $form.trigger('submit');
        }

        if ($target.hasClass('js_del_filter')) {
            var $catalog = $target.closest('.re-catalog');
            var $form = $catalog.find('.js-ajax-filter');
            var elementSlug = $target.data('filter-slug');
            var $field = $form.find('[value="' + elementSlug + '"]');

            if ($field.prop('tagName').toLowerCase() === 'input') {
                if ($field.attr('type') === 'radio') {
                    $form.find('input[name="type_build"]').prop('checked', false);
                } else {
                    $field.prop('checked', false);
                }
            } else if ($field.prop('tagName').toLowerCase() === 'option') {
                $field.parent().val('').trigger('change');
            }
            $form.trigger('submit');
        }

        if ($target.hasClass('js_clear_all')) {
            var $catalog = $target.closest('.re-catalog');
            var $form = $catalog.find('.js-ajax-filter');

            $form.find('input[type="checkbox"]').prop('checked', false);
            $form.find('input[type="radio"]').prop('checked', false);
            $form.find('select').val('').trigger('change');
            $form.trigger('submit');
        }
    });

    $(window).on('scroll', function () {
        var $nav = $('.re-navbar');
        if ($(window).scrollTop() > 40) {
            $nav.addClass('scrolled');
        } else {
            $nav.removeClass('scrolled');
        }
    });

}( jQuery ));
