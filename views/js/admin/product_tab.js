function isChecked(element) {
    return element.checked;
}

function getValue(element) {
    return element.value;
}

function WeekdayWidget(element) {
    var parts = Array.apply(null, element.querySelectorAll('.week-parts [type=checkbox]'));
    var days = Array.apply(null, element.querySelectorAll('.days [type=checkbox]'));

    function value() {
        return days.filter(isChecked).map(getValue);
    }

    this.value = value;

    function updateParts(selected) {

        function notSelected(val) {
            return selected.indexOf(val) === -1;
        }

        parts.forEach(function (part) {
            var partDays = part.dataset.values.split(',');
            var notSelectedParts = partDays.filter(notSelected);
            if (notSelectedParts.length === 0) {
                part.checked = true;
                part.indeterminate = false;
            } else if (notSelectedParts.length === partDays.length) {
                part.checked = false;
                part.indeterminate = false;
            } else {
                part.indeterminate = true;
            }
            if (partDays.length === partDays.filter(notSelected).length)
                part.checked = partDays.filter(notSelected).length === 0;
        });
    }

    function updateDays(values, checked) {
        days.forEach(function (ele) {
            if (values.indexOf(ele.value) > -1) {
                ele.checked = checked;
            }
        });
    }

    element.addEventListener('change', function (event) {
        if (event.target.tagName === 'INPUT') {
            if (event.target.name === element.dataset.name) {
                updateParts(value());
            } else {
                updateDays(event.target.dataset.values.split(','), event.target.checked);
                updateParts(value());
            }
        }
    });
}

function blinking(element){
    element.addClass('err');
    setTimeout(function () {
        element.removeClass('err');
    }, 300);
    setTimeout(function () {
        element.addClass('err');
    }, 300);

}



jQuery(document).ready(function () {
    var servise_product = $('#service_active').val();
    var type_step = $('#type-step').val();

    switch (type_step) {
        case  '1':
            $('.step-hours').addClass('active');
            break;
        case  '2':
            $('.step-days').addClass('active');
            break;
    }
    if (servise_product == 1) {
        $('.service-product_active').addClass('active');
        $('.service-product').find('.switch-input').addClass('-checked');
    }

    $('.switch-input').click(function (e) {
        e.preventDefault();
        var _this = $(this);
        if (_this.hasClass('-checked')) {
            _this.closest('.service-product').find('.service-product_active').addClass('active');
        } else {
            _this.closest('.service-product').find('.service-product_active').removeClass('active');
        }
    });

    $('body').on( 'click', '#service_active', function (e) {
        if ($('#service_active').is(':checked')) {
            $('.service-product_active').addClass('active');
            $('#service_active').attr('value',1);
        } else {
            $('.service-product_active').removeClass('active');
            $('#service_active').attr('value',0);
        }
    });


    $('#type-step').on('change', function (e) {
        e.preventDefault();
        $('.step-wrapp').find('.select2-selection--single').removeClass('err')
        var typeStep = $(this).val();

        var stepHours = $(this).closest('.step-wrapp').find('.step-hours');
        var stepDays = $(this).closest('.step-wrapp').find('.step-days');
        switch (typeStep) {
            case '0':
                stepHours.removeClass('active');
                stepDays.removeClass('active');
                break;
            case '1' :
                stepHours.addClass('active');
                stepDays.removeClass('active');
                break;
            case '2' :
                stepDays.addClass('active');
                stepHours.removeClass('active');
                break;
        }

    });

    $('body').on('click', '#submit_service', function (e) {
        e.preventDefault();
        var _this = $(this);
        var service_active = _this.closest('.service-product').find('.switch-input').hasClass('-checked') ? 1 : 0;
        var _this_spa = _this.closest('.service-product_active');
        var start = _this_spa.find('#start').val();
        var end = _this_spa.find('#end').val();
        var typeStep = _this_spa.find('#type-step').val();
        var stepTime = _this_spa.find('#step_time').val();
        var stepDays = _this_spa.find('#step_days').val();
        var id_service = _this_spa.find('#id_service').val();
        var id_product = _this_spa.find('#id_product').val();
        var working_days = [];
        var days = Array.apply(null, document.getElementById('days').querySelectorAll('.days [type=checkbox]'));
        $(days).each(function (i) {
            if ($(this).prop('checked'))
                working_days[i] = $(this).val();
        });

        if (typeStep == 0) {
            blinking($('.step-wrapp').find('.select2-selection--single'));
            return;
        }

        var data = {
            ajax: 'save_service',
            service_active: service_active,
            start: start,
            end: end,
            typeStep: typeStep,
            stepTime: stepTime,
            stepDays: stepDays,
            id_service: id_service,
            id_product: id_product,
            working_days: working_days
        };

        if(id_service != undefined) {
            $.ajax({
                type: 'POST',
                url: '/modules/servicesproduct/ajax.php',
                data: {
                    ajax: 'checkService',
                    id_service: id_service
                },
                success: function (request) {
                    var st_time = JSON.parse(request);
                    if(request && st_time.step_time != stepTime) {
                        $('.b-popup-content').html(
                            '<div class="wrapp-close"><a href="#close" title="Close" class="closeModal">X</a></div>'+
                            '<div>When you save, existing entries will be delete! Do you want to continue?</div>'+
                            '<div><input type="button" id="confirmation" class="btn" value="Save"/></div>'
                        );
                        confirmation.onclick = function(e) {
                            $('.b-popup').removeClass('view-popup');
                            data['remove_orders'] = true;
                            ajaxServiceSave(data);
                        };
                        $('.b-popup').addClass('view-popup');
                    } else {
                        ajaxServiceSave(data);
                    }
                }
            });
        } else {
            ajaxServiceSave(data);
        }
    });
});

function ajaxServiceSave(data) {
    $.ajax({
        type: 'POST',
        url:   '/modules/servicesproduct/ajax.php',
        data: data,
        success: function (request) {
            $('#submit_service').closest('.service-product_active').find('#id_service').attr('value', request);
            if (request) {
                $('.b-popup-content').text('Update successfully!');
                $('.b-popup').addClass('view-popup');
                setTimeout(function () {
                    $('.b-popup').removeClass('view-popup');
                }, 2000);
            } else {
                $('.b-popup-content').text('Not update try again later!');
                $('.b-popup').addClass('view-popup');
                setTimeout(function () {
                    $('.b-popup').removeClass('view-popup');
                }, 2000);
            }
        }
    });
}