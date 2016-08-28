(function ($) {
    'use strict';
    var lunchBox = [];

    // --------------------------------------------------------------
    // AJAX SET UP
    // --------------------------------------------------------------

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --------------------------------------------------------------
    // Fittext.JS
    // --------------------------------------------------------------

    $(".jumbo > h1").fitText(1.5);


    // --------------------------------------------------------------
    // VIOLENTLY SHAKE AN ELEMENT
    // --------------------------------------------------------------

    function shakeElement(selector) {
        var l = 15;
        for( var i = 0; i < 5; i++ )
            $(selector).animate( {
                'margin-left': "+=" + ( l = -l ) + 'px',
                'margin-right': "-=" + l + 'px'
            }, 50);
    }


    // --------------------------------------------------------------
    // TICK COMPLETED IF AVAILABLE
    // --------------------------------------------------------------

    $(window).on('load', function () {
        $(".trigger").toggleClass("drawn");
    });

    // --------------------------------------------------------------
    // MAKE ORDER BUTTON
    // --------------------------------------------------------------

    $('#make-order-btn').on('click', function (e) {
        e.preventDefault();

        var btn = $(this);

        if (btn.attr('disabled') == "disabled") {
            return;
        }

        var buka = $('.container.foods > .whitehouse');
        var bukaModal = $('.buka-select');

        // Buka Select Modal
        bukaModal.modal({ backdrop: 'static' });

        // Buka Select Modal Link
        $('.buka-select .bukas a').off().on('click', function (e) {
            e.preventDefault();

            var bukaName = $(this).data('display-buka');
            var buka     = $('.buka.'+bukaName);

            // Set the active buka ID
            $('.container.foods').data('active-buka', $(this).data('buka-id'));

            // show
            buka.show();

            // Buka modal close
            bukaModal.modal('hide');

            // Animate to the buka...
            $('html, body').animate({ scrollTop: buka.offset().top }, 500);
        });

        // Reactivate button...
        $('.buka-select .close').on('click', function () {
            btn.removeAttr('disabled');
        });

        // disable btn
        $(this).attr('disabled', "disabled");
    });

    // --------------------------------------------------------------
    // ORDER
    // --------------------------------------------------------------

    $('.container.foods .food').off().on('click', function () {
        var orderIndex   = false;
        var matchedOrder = false;
        var lunch = $(this).data('lunch');

        // Elements...
        var overviewModal  = $('#order-overview');
        var overviewAlert  = $('#order-overview .alert');
        var closeOrderBtn  = $('.food-modal .close-order');
        var addOrderBtn    = $('.food-modal .add-to-order');
        var removeOrderBtn = $('.food-modal .remove-order');
        var foodSelectionPalette = $('.food.lunch-'+lunch.id);
        var foodModal      = $('.food-modal');
        var bukaTitleElem  = $('.container.foods .order-resturant');

        // Check if the order exists...
        lunchBox.forEach(function (order, index) {
            if (orderIndex === false && order.id === lunch.id) {
                orderIndex  = index;
                matchedOrder = lunchBox[index];
            }
        });

        addOrderBtn.removeAttr('disabled');
        closeOrderBtn.removeAttr('disabled');

        // Update Mode...
        if (orderIndex !== false && typeof matchedOrder == 'object') {
            $('#amount-input').val(matchedOrder.cost);
            $('#amount-servings').val(matchedOrder.servings);

            addOrderBtn.text('Update Order');
            removeOrderBtn.removeClass('hide');
        }

        // Add mode...
        else {
            removeOrderBtn.addClass('hide');
            addOrderBtn.text('Add to Order');
        }

        // Event when modal is hidden
        $('.food-modal').on('hidden.bs.modal', function(){
            $('#amount-input').val("");
            $('#amount-servings').val("1");
            $('#add-note').val("");
            $('.modal-backdrop').remove();
            $('.food-modal .error').removeClass('active');
        });

        // Event when modal is shown
        $('.food-modal').on('show.bs.modal', function () {
            $('.food-modal .modal-title').html(lunch.name);
            $('.food-modal .modal-cost').html(lunch.cost);

            if (lunch.cost > 0.1) {
                $('.food-modal .fixed-cost').show();
                $('.food-modal .no-fixed-cost').hide();
            } else {
                $('.food-modal .fixed-cost').hide();
                $('.food-modal .no-fixed-cost').show();
            }
        });

        // Show modal
        foodModal.modal({ backdrop: 'static' });

        // Disable conventional form submit
        $('#single-order').on('submit', function (e) {
            e.preventDefault();
        });

        // -----------------------------
        // Food Order -> Add
        // -----------------------------

        addOrderBtn.off().on('click', function () {
            var modalInputError = $('.food-modal .error');

            // Validate...
            var amountInput    = parseInt($('#amount-input').val());
            var amountServings = parseInt($('#amount-servings').val());

            // Convert to number
            amountInput    = isNaN(amountInput) ? 0 : amountInput;
            amountServings = isNaN(amountServings) ? 0 : amountServings;

            var additionalNotes = $('#add-note').val();

            modalInputError.removeClass('active');

            if (lunch.cost <= 0.0 && amountInput <= 0.0) {
                modalInputError.addClass('active');
                return;
            }

            if (lunch.cost > 0.0 && amountServings <= 0.0) {
                modalInputError.addClass('active');
                return;
            }

            // Loading...
           closeOrderBtn.attr('disabled', true);

            // Collate order
            var order = {
                id: lunch.id,
                name: lunch.name,
                servings: lunch.cost >= 0.1 ? amountServings : 1,
                cost: lunch.cost >= 0.1 ? lunch.cost : amountInput,
            };

            if (additionalNotes.length > 1 && additionalNotes != ' ') {
                order.note = additionalNotes;
            }

            order.totalCost = order.cost * order.servings;

            if (matchedOrder === false) {
                lunchBox.push(order);
            } else {
                lunchBox[orderIndex] = order;
            }

            // Activate
            foodSelectionPalette.addClass('active');
            foodModal.modal('hide');

            toggleCompleteOrderSlab();
        });

        // -----------------------------
        // Food Order -> Remove
        // -----------------------------

        removeOrderBtn.off().on('click', function () {
            if (matchedOrder === false)
                return;

            // Remove from order...
            lunchBox.splice(orderIndex, 1);

            foodSelectionPalette.removeClass('active');
            foodModal.modal('hide');

            toggleCompleteOrderSlab();
        });

        // -----------------------------
        // Complete Order Slab
        // -----------------------------

        function toggleCompleteOrderSlab() {
            var baseCost  = bukaTitleElem.data('buka-base-cost');
            var totalCost = baseCost;

            // Elements...
            var completeOrderSlab = $('#complete-order');
            var baseCostElem      = $('#complete-order .baseCost');
            var totalCostElem     = $('#complete-order .totalCost');

            // Calculate final cost...
            lunchBox.forEach(function(order) {
                totalCost += order.totalCost;
            });

            if (totalCost > 0) {
                baseCostElem.text(parseFloat(baseCost));
                totalCostElem.text(parseFloat(totalCost));
                completeOrderSlab.addClass('active');

                if (baseCost <= 0) {
                    baseCostElem.parent().hide();
                }
            } else {
                completeOrderSlab.removeClass('active');
            }
        };

        // -----------------------------
        // Generate Costs Overview Table
        // -----------------------------

        function generateCostsTable(orders, baseCost) {
            var tbl       = document.getElementsByClassName('order-overview-table')[0];
            var tblBody   = document.createElement("tbody");
            var baseCost  = isNaN(parseInt(baseCost)) ? 0 : parseInt(baseCost);
            var totalCost = baseCost;

            // Clean the table
            tbl.innerHTML = "";

            for (var i = 0; i < orders.length; i++) {
                totalCost += orders[i].totalCost;

                var servings = orders[i].servings;
                var name     = servings+" "+orders[i].name+(servings > 1 ? "s" : "");
                var cost     = orders[i].totalCost;

                // Add order as table row
                tblBody.appendChild(createTableRowData(name, cost));
            }

            if (baseCost > 0) {
                tblBody.appendChild(createTableRowData('Buka Base Cost', baseCost));
            }

            // Add total as table row...
            tblBody.appendChild(createTableRowData('Total Cost', totalCost));

            tbl.appendChild(tblBody);

            function createTableRowData(name, cost) {
                var row = document.createElement("tr");
                var th  = document.createElement("th");
                var td  = document.createElement("td");

                th.setAttribute('scope', 'row');
                td.setAttribute('class', 'right');

                th.innerHTML = name;
                td.innerHTML = "&#8358;" + cost;

                row.appendChild(th);
                row.appendChild(td);

                return row;
            }
        }

        // ------------------------------
        // FREE LUNCH APPLY
        // ------------------------------

        $('#apply-freelunch').off().on('click', function () {
            var btn = $(this);
            var orderBtn = $('#finalize-order');
            var isActivated = orderBtn.data('free-lunch') == true;

            function toggleButtonText() {
                var oldText = btn.text();
                btn.text(btn.data('alt-text')).data('alt-text', oldText);
            }

            if (isActivated) {
                toggleButtonText();
                orderBtn.data('free-lunch', false);
                $('.free-lunch-alert').fadeOut();
            } else {
                var singleCashValue = $(this).data('free-lunch-value');
                var freeLunchCount  = $(this).data('free-lunch-count');

                var actualCashValue = singleCashValue * freeLunchCount;
                var totalFoodCost   = parseInt($('#complete-order .totalCost').text());
                var showMessage     = actualCashValue > totalFoodCost ? '.totally' : '.partially';

                if (showMessage == '.partially') {
                    $('.free-lunch-alert .partially strong').html("&#8358;"+actualCashValue);
                }

                // Show message depending on the weight of the free lunches...
                $('.free-lunch-alert '+showMessage).show();

                // Add whether using free lunch to place order btn...
                orderBtn.data('free-lunch', true);

                toggleButtonText();

                // Display the offset...
                $('.free-lunch-alert').fadeIn();

            }
        });

        // -----------------------------
        // Complete Order
        // -----------------------------

        $('.complete-btn').off().on('click', function () {
            var baseCost  = bukaTitleElem.data('buka-base-cost');
            showHideOverviewAlerts();
            generateCostsTable(lunchBox, baseCost);
        });

        // -----------------------------
        // Send Order
        // -----------------------------

        $('#finalize-order').off().on('click', function () {
            var self = $(this);

            self.button('loading');

            // SEND REQUEST ---------------------

            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: self.data('url'),
                data: {
                    orders:     lunchBox,
                    buka_id:    $('.container.foods').data('active-buka'),
                    free_lunch: self.data('free-lunch') == true ? 1 : 0
                },
                success: function (data) {
                    showHideOverviewAlerts('success');

                    window.setTimeout(function() {
                        var url = self.data('redirect');
                        window.location = url.replace(':id', data.id);
                    }, 2000);
                },
                error: function () {
                    shakeElement('#order-overview');
                    showHideOverviewAlerts('error');

                    self.button('reset');
                }
            });
        });

        function showHideOverviewAlerts(intent) {
            var elem;

            overviewAlert.hide();

            switch (intent) {
                case 'success':
                    elem = $('#order-overview .alert-success');
                    break;
                case 'error':
                    elem = $('#order-overview .alert-danger');
                    break;
            }

            if (elem !== undefined) {
                elem.show();
                window.setTimeout(function () { elem.slideUp(); }, 10000);
            }
        }
    });

    // --------------------------------------------------------------
    // ORDER OVERVIEW
    // --------------------------------------------------------------

    $('.order-history .open-overview').on('click', function (e) {
        var lunchbox, orders, baseCost, totalCost, baseCostElem, totalCostElem;
        e.preventDefault();

        baseCostElem = $('#order-overview tr.baseCost');
        totalCostElem = $('#order-overview .right.totalCost');

        if ( ! HNGOrderHistory || ! (lunchbox = HNGOrderHistory[$(this).data('lunchbox-id')])) {
            console.error("Invalid lunchbox ID. Weird.");
            return;
        }

        // Calculate Orders...
        orders = (function(){
            var orders = {};

            $.each(lunchbox, function (index, lunch) {
                orders[index] = {
                    name: lunch.name,
                    cost: lunch.cost * lunch.servings
                };
            });

            return orders;
        }());

        baseCost  = parseInt($(this).data('base-cost'));
        totalCost = parseInt($(this).data('total-cost'));

        showOrderOverview(orders, baseCost, totalCost);

        // Remove on Close Event...

        function showOrderOverview(orders, baseCost, totalCost) {
            var tbody = $('#order-overview tbody.order-details');

            // Show/Hide the base cost element
            (baseCost > 0) ? baseCostElem.show() : baseCostElem.hide();
            baseCostElem.find('.right').first().html("&#8358;"+baseCost);
            totalCostElem.html("&#8358;"+totalCost);

            tbody.children('.single-order').remove();

            $.each(orders, function (index, lunch) {
                var current,
                    tr = $('<tr class="single-order"></tr>'),
                    td = $('<td class="right"></td>'),
                    th = $('<th class="row"></th>');

                th.text(lunch.name);
                td.html("&#8358;"+lunch.cost);

                tr.append(th);
                tr.append(td);

                current = tbody.html();
                tbody.html(tr);
                tbody.append(current);
            });
        };

        $('#order-overview').modal('show');
    });


    // --------------------------------------------------------------
    // CLICK EDIT USER LINK
    // --------------------------------------------------------------

    $('a.table-link').off().on('click', function (e) {
        e.preventDefault();

        var user = $(this).data('user');

        var userDetails = {
            id: user.id,
            role: user.role,
            name: user.name,
            wallet: user.wallet,
            roles: $(this).data('roles'),
            freelunch: $(this).data('freelunches'),
        };

        $('.update-user .modal-title span.name').text(userDetails.name);
        $('.form-control.freelunch').val(userDetails.freelunch);
        $('#user-role').val(user.role).change();

        // Listen to Update User Button Clicks...
        $('.submit-user-change').off().on('click', function () {
            $(this).attr('disabled', 'true');
            $('.saving-changes').addClass('active');

            var userDetails = {
                user_id: user.id,
                role: parseInt($('#user-role').val()),
                freelunch: parseInt($('.form-control.freelunch').val()),
            };

            // send data for processing...
        });
    });



    // --------------------------------------------------------------
    // NUMBER SELECT
    // --------------------------------------------------------------

    $('.btn-number').click(function(e){
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {

                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function(){
        $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function() {
        var minValue =  parseInt($(this).attr('min'));
        var maxValue =  parseInt($(this).attr('max'));
        var valueCurrent = parseInt($(this).val());
        var name = $(this).attr('name');

        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }
    });
    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


}(jQuery));