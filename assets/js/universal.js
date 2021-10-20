let eventRows = [];
let guideText = ``;
let guideCount = `<div role="group" class="input-group mt-1">
<div class="input-group-prepend">
    <div class="input-group-text text-nowrap"><span class="badge badge-primary">{short_date}</span></div>
</div> <input name="input_person[]" type="number" placeholder="Enter the number of people" min="1" max="60"
    class="input_person enterNextTab form-control" data-refi="{refi}" data-date="{datei}" >
</div>`;

(($) => {
    $(document).ready(function () {
        

        let rsvBtn = $(`.reservation-button`);
        let rsvBtnTxt = rsvBtn.html();

        let doc = $(document);
        let body = $(`body`);
        let rsvFrmHolder = $(`.rsv-form-holder`);
        let rsvFrm = $(`.rsv-form`);

        doc.on(`scroll`, function (e) {
            if ($(document).scrollTop() > 200) {
                rsvBtn.fadeIn(200);
            } else {
                rsvBtn.fadeOut(200);
            }
        });

        rsvBtn.on(`click`, function (e) {
            $(`.rsv-form-holder`).remove();
            rsvBtn.html(`Loading...`);

            let data = {
                action: "get_reservation_form",
                nonce: mvr_nonce.get_reservation_form,
            };

            $.ajax({
                type: "POST",
                url: mvr.ajax_url,
                data,
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        rsvBtn.html(rsvBtnTxt);
                        body.append(response.data.form);

                        $(`.rsv-form`).fadeIn(400, function (e) {
                            $(this).css({
                                display: `flex`,
                            });

                            // Event adder on document ready
                            startConfig();
                            rsvFormControls();
                            rsvTabTravel();

                        });
                    }
                },
                error: function (e) {
                    alert(`Error!`);
                },
            });
        });

        lc_switch(".rsv-form input[type=checkbox]", {
            // (string) "checked" status label text
            on_txt: "ON",

            // (string) "not checked" status label text
            off_txt: "OFF",

            // (string) custom "on" color. Supports also gradients
            on_color: false,

            // (bool) whether to enable compact mode
            compact_mode: false,
        });




    });
})(jQuery);

// alert(["Rif", "at", "ro"].indexOf("at"));

function rsvFormControls() {

    let $ = jQuery;
    let btn = $(`.rsv-form-button`);
    let rsvBtn1 = btn;
    let clsBtn = $(`.close-rsv-form`);
    let resetBtn = $(`.rsv-reset-button`);
    let personBtn = $(`.person-config`);

    clsBtn.on(`click`, function (e) {
        $(`.rsv-form`).fadeOut(400, function (e) {
            $(`.rsv-form-holder`).remove();
        });
        eventRows = [];
    });

    rsvBtn1.on(`click`, function (e) {
        let parent = $(this).parents(`.rsv-section.active`);
        parent.fadeOut(400, function (e) {
            let next = $(`.rsv-section`).eq($(this).index());
            next.fadeIn(400);
            parent.removeClass(`active`);
            next.addClass(`active`);

            // $(this).hide(0, function (e) {
            // });
            // $(this).remove();
        });
    });

    resetBtn.on(`click`, function (e) {
        if (confirm(`Reset chosen dates?`)) {
            $(`.calendar-day`).removeClass(`selected`);
            eventRows = [];
        }
    });

    personBtn.on(`click`, function (e) {
        let parent = $(this).parents(`.rsv-section.active`);
        parent.fadeOut(400, function (e) {
            let next = $(`.rsv-section`).eq($(this).index());
            next.fadeIn(400);
            parent.removeClass(`active`);
            next.addClass(`active`);

            // $(this).hide(0, function (e) {
            // });
            // $(this).remove();
        });
    });

    $(`.create-invoice`).on(`click`, function (e) {
        collectData();
    })
}

function select_day_options(t) {
    let $ = jQuery;
    let self = $(t);
    let date = self.data(`ref`);

    if (self.hasClass(`disabled`)) return;

    if (self.hasClass(`selected`)) {
        if (confirm(`Are you sure to remove this date?`)) {
            self.toggleClass(`selected`);

            eventRows = eventRows.filter((item) => {
                return item != date;
            });
        }
    } else {
        self.toggleClass(`selected`);
        if (eventRows.indexOf(date) == -1) eventRows.push(date);
    }

    handleButtons();
}

/**
 * Insert guides selection
 */
function startConfig() {
    let $ = jQuery;
    let btn = $(`.rsv-config-dates`);
    let guideCard = $(
        `.tab-card.card-guides .multiple-selector`);
    let card = $(`.rsv-section-step-4 .multiple-selector`);


    btn.on(`click`, function (e) {
        eventRows.forEach((item, i) => {
            let shortDate = splittedDate(item);
            card.append(
                guideCount
                    .replace(`{refi}`, i)
                    .replace(`{datei}`, item)
                    .replace(`{short_date}`, shortDate)
            );
            guideCard.append(
                guideText
                    .replace(`{refi}`, i)
                    .replace(`{datei}`, item)
                    .replace(`{short_date}`, shortDate)
            )
        });
        divideProductsDate();
    });
}

/**
 * Tab navigation function
 */
function rsvTabTravel() {
    let $ = jQuery;
    let parent = $(`.rsv-tab`);
    let nextBtn = parent.find(`.rsv-next-tab`);
    let prevBtn = parent.find(`.rsv-prev-tab`);
    let keys = parent.find(`.tab-key`);
    let cards = parent.find(`.tab-card`);
    let currentKey = parent.find(`.tab-key.active`);
    let currentCard = parent.find(`.tab-card.active`);

    nextBtn.on(`click`, function (e) {
        if (validateFields() == false) return;
        currentKey.removeClass(`active`).next().addClass(`active`);
        currentCard.removeClass(`active`).next().addClass(`active`);

        // Change current ones
        currentKey = parent.find(`.tab-key.active`);
        currentCard = parent.find(`.tab-card.active`);
    });

    prevBtn.on(`click`, function (e) {
        if (validateFields() == false) return;
        currentKey.removeClass(`active`).prev().addClass(`active`);
        currentCard.removeClass(`active`).prev().addClass(`active`);

        // Change current ones
        currentKey = parent.find(`.tab-key.active`);
        currentCard = parent.find(`.tab-card.active`);
    });

    keys.on(`click`, function (e) {
        if (validateFields() == false) return;

        keys.removeClass(`active`);
        cards.removeClass(`active`);
        // Class addition
        $(this).addClass(`active`);
        cards.eq($(this).index()).addClass(`active`);
        // Change current ones
        currentKey = parent.find(`.tab-key.active`);
        currentCard = parent.find(`.tab-card.active`);
    });
}

/**
 * Tab navigation function
 */
function dateTabTravel(parentEL) {
    let $ = jQuery;
    let parent = $(parentEL);
    let nextBtn = parent.find(`.rsv-next-tab, .date-next-tab`);
    let prevBtn = parent.find(`.rsv-prev-tab, date-next-tab`);
    let keys = parent.find(`.tab-key, .date-tab-key`);
    let cards = parent.find(`.tab-card, .date-tab-card`);

    keys.eq(0).addClass(`active`);
    cards.eq(0).addClass(`active`);

    let currentKey = parent.find(`.tab-key.active, .date-tab-key.active`);
    let currentCard = parent.find(`.tab-card.active, .date-tab-card.active`);

    nextBtn.on(`click`, function (e) {
        if (validateFields() == false) return;
        currentKey.removeClass(`active`).next().addClass(`active`);
        currentCard.removeClass(`active`).next().addClass(`active`);

        // Change current ones
        currentKey = parent.find(`.tab-key.active, .date-tab-key.active`);
        currentCard = parent.find(`.tab-card.active, .date-tab-card.active`);
    });

    prevBtn.on(`click`, function (e) {
        if (validateFields() == false) return;
        currentKey.removeClass(`active`).prev().addClass(`active`);
        currentCard.removeClass(`active`).prev().addClass(`active`);

        // Change current ones
        currentKey = parent.find(`.tab-key.active, .date-tab-key.active`);
        currentCard = parent.find(`.tab-card.active, .date-tab-card.active`);
    });

    keys.on(`click`, function (e) {
        if (validateFields() == false) return;

        keys.removeClass(`active`);
        cards.removeClass(`active`);
        // Class addition
        $(this).addClass(`active`);
        cards.eq($(this).index()).addClass(`active`);
        // Change current ones
        currentKey = parent.find(`.tab-key.active, .date-tab-key.active`);
        currentCard = parent.find(`.tab-card.active, .date-tab-card.active`);
    });
}

/**
 * Validate RSV steps fields
 * @returns bool
 */
function validateFields() {
    let $ = jQuery;
    let result = true;
    let mealFields = $(`.card-meal input[type="number"]`);

    return result;
}

/**
 * Hide or show calender buttons
 */
function handleButtons() {
    let $ = jQuery;
    let chosenCount = $(`.rsv-form .calendar-day.selected`).length;
    let buttons = $(
        `.rsv-section-step-2 .rsv-form-button, .rsv-section-step-2 .rsv-reset-button`
    );

    if (chosenCount > 0 && buttons.hasClass(`invisible`)) {
        buttons.removeClass(`invisible`).css({
            display: `none`,
        });
        buttons.fadeIn(400);
    } else if (chosenCount == 0) {
        buttons.addClass(`invisible`);
    }
}

function splittedDate(date) {
    let shortDate = date.toString().split(`/`);
    return shortDate[0] + `/` + shortDate[1];
}

function divideProductsDate() {
    let $ = jQuery;
    let accommodation = $(`.card-accommodation .mvr-product-list`);
    let vehicles = $(`.card-vehicles .mvr-product-list`);
    let equipements = $(`.card-equipements .mvr-product-list`);
    let accommodationCL = accommodation.html();
    let vehiclesCL = vehicles.html();
    let equipementsCL = equipements.html();
    let accommodationKEY = ``;
    let vehiclesKEY = ``;
    let equipementsKEY = ``;
    let accommodationTAB = ``;
    let vehiclesTAB = ``;
    let equipementsTAB = ``;

    eventRows.forEach((date) => {
        let shortDate = splittedDate(date);
        // Keys
        accommodationKEY =
            accommodationKEY +
            `<div class="date-tab-key" data-date="${date}"><span>${shortDate}</span></div>`;
        vehiclesKEY += `<div class="date-tab-key" data-date="${date}"><span>${shortDate}</span></div>`;
        equipementsKEY += `<div class="date-tab-key" data-date="${date}"><span>${shortDate}</span></div>`;
        // Tabs
        accommodationTAB += `<div class="date-tab-card" data-date=${date}>${accommodationCL}</div>`;
        vehiclesTAB += `<div class="date-tab-card" data-date=${date}>${vehiclesCL}</div>`;
        equipementsTAB += `<div class="date-tab-card" data-date=${date}>${equipementsCL}</div>`;
    });

    accommodation.replaceWith(
        `<div class="date-tab date-accommodation-tab"><div class="date-tab-header">${accommodationKEY}</div><div class="date-tab-body">${accommodationTAB}</div></div>`
    );
    vehicles.replaceWith(
        `<div class="date-tab date-vehicles-tab"><div class="date-tab-header">${vehiclesKEY}</div><div class="date-tab-body">${vehiclesTAB}</div></div>`
    );
    equipements.replaceWith(
        `<div class="date-tab date-equipements-tab"><div class="date-tab-header">${equipementsKEY}</div><div class="date-tab-body">${equipementsTAB}</div></div>`
    );

    dateTabTravel(`.date-accommodation-tab`);
    dateTabTravel(`.date-vehicles-tab`);
    dateTabTravel(`.date-equipements-tab`);
}

// function removeRow(t) {
//     let $ = jQuery;
//     var e = this.trans.get(
//         "__JSON__.reservation.Vous désirez supprimer cette date de réservation?"
//     );
//     if (confirm(e)) {
//         var n = eventRows.findIndex(function (e) {
//             return e.ref === t;
//         });
//         $('.calendar-day[data-ref="' + t + '"].selected').removeClass(
//             "selected"
//         ),
//             eventRows.length > 1
//                 ? (eventRows.splice(n, 1),
//                   eventRows.length < 2 &&
//                       this.modify_person_count &&
//                       (this.modify_person_count = !1))
//                 : ((this.modify_person_count = !1),
//                   (eventRows = []),
//                   $("#selectionNext").addClass("d-none")),
//             this.localSaveUpdatedReservation();
//     }
// }

// function addRow(t) {
//     let $ = jQuery;
//     var e = null;
//     if (this.modify_person_count) {
//         for (index in eventRows)
//             if (eventRows[index].person_count > 0) {
//                 e = eventRows[index].person_count;
//                 break;
//             }
//     } else eventRows[0] && (e = eventRows[0].person_count);
//     if (this.isMobile) var n = null;
//     else n = 0;
//     eventRows.push({
//         ref: t,
//         discount3services: !1,
//         discount3nights: !1,
//         editTab: !1,
//         guideAvailabilityUpdatedAt: null,
//         vehicleAvailabilityUpdatedAt: null,
//         hebergementAvailabilityUpdatedAt: null,
//         gearAvailabilityUpdatedAt: null,
//         restaurationAvailabilityUpdatedAt: null,
//         person_count: e,
//         guide_count: n,
//         spa_count: 0,
//         guide_options: [],
//         vehicle_options: [],
//         gear_options: [],
//         hebergement_options: [],
//         restauration_options: [],
//         discount_total: 0,
//         subTotal: 0,
//     }),
//         eventRowAdded();
// }
// function eventRowAdded() {}
// function orderSelection() {
//     eventRows = eventRows.slice().sort(function (t, e) {
//         var n = t.ref.split("/");
//         t = new Date(n[2], n[1] - 1, n[0]);
//         var r = e.ref.split("/");
//         return t - (e = new Date(r[2], r[1] - 1, r[0]));
//     });
// }

function personChooser() {
    let $ = jQuery;
    let parent = $(`.rsv-section-step-4`);
    let singleSelect = parent.find(`.single-selector`);
    let multipleSelect = parent.find(`.multiple-selector`);

    lc_switch("#choose_person", {
        on_txt: "ON",
        off_txt: "OFF",
        on_color: false,
        compact_mode: true,
    });
    $("#choose_person").on("lcs-on", function (e) {
        singleSelect.fadeOut(300, function (e) {
            multipleSelect.fadeIn(300);
        });
    });
    $("#choose_person").on("lcs-off", function (e) {
        multipleSelect.fadeOut(300, function (e) {
            singleSelect.fadeIn(300);
        });
    });
    $(`#choose_person_input`).on(`change`, function (e) {
        $(`.rsv-section-step-4 .multiple-selector input`).val($(this).val());
    });
}

function collectData() {
    let $ = jQuery;
    let result = [];

    // Date wise items/objects
    let personCount = $(`.rsv-section-step-4 .multiple-selector`);
    let guideCount = $(`.card-guides .multiple-selector`);
    let accommodations = $(`.card-accommodation`);
    let vehicles = $(`.card-vehicles`);
    let equipements = $(`.card-equipements`);

    eventRows.forEach((item, i) => {
        result[i] = {};
        result[i].date = item;
        result[i].person = personCount.find(`[data-date="${item}"]`).val();
        result[i].guide = guideCount.find(`select[data-date="${item}"]`).val();
        result[i].accommodation = [];
        result[i].vehicles = [];
        result[i].equipements = [];

        accommodations
            .find(`.date-tab-card[data-date="${item}"] .mvr-product`)
            .each(function () {
                let self = $(this);
                let quantity = self.find(`select`).val()
                if (quantity != null) result[i].accommodation.push({
                    id: self.data(`id`),
                    quantity: quantity,
                });
            });

        vehicles
            .find(`.date-tab-card[data-date="${item}"] .mvr-product`)
            .each(function () {
                let self = $(this);
                let quantity = self.find(`select`).val()
                if (quantity != null) result[i].vehicles.push({
                    id: self.data(`id`),
                    quantity: self.find(`select`).val(),
                });
            });

        equipements
            .find(`.date-tab-card[data-date="${item}"] .mvr-product`)
            .each(function () {
                let self = $(this);
                let quantity = self.find(`select`).val()
                if (quantity != null) result[i].equipements.push({
                    id: self.data(`id`),
                    quantity: self.find(`select`).val(),
                });
            });

    });

    let data = {
        action: `handle_data_collection`,
        nonce: mvr_nonce.handle_data_collection,
        data: result
    }

    loading($(`.card-invoice`));

    $.ajax({
        type: "POST",
        url: mvr.ajax_url,
        data,
        dataType: "JSON",
        success: function (response) {
            console.log(response)
            if (response.success) {
                $(`.card-invoice`).html(response.data.invoice)
            } else {
                alert(response.data.msg)
            }
        }
    });

    console.log(result);
}


function navigateCalendar() {
    let $ = jQuery;
    let parent = $(`.calendar-holder`);
    let prevBtn = parent.find(`.btn-prev`)
    let nextBtn = parent.find(`.btn-next`)
    let btns = parent.find(`.btn-next, .btn-prev`)

    btns.on(`click`, function (e) {
        e.preventDefault();
        loading(parent)
        let self = $(this);
        let month = self.data(`month`);
        let year = self.data(`year`);
        let data = {
            month, year, controls: `ajax_month`, action: `get_ajax_calendar`, nonce: mvr_nonce.get_ajax_calendar
        }

        $.ajax({
            type: "POST",
            url: mvr.ajax_url,
            data,
            dataType: "JSON",
            success: function (response) {
                parent.html(response.data.calendar)
                console.log(response)
            },
            error: function (res) {
                alert(`ERROR`)
            }
        });
    })

    eventRows.forEach(date => {
        $(`.calendar-day[data-ref="${date}"]`).addClass(`selected`)
    });
}


function loading(el) {
    let $ = jQuery;

    $(el).html(
        `<div class="loading-holder">
        <div class="loader"></div>
        </div>`
    )
}