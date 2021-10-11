<div class="rsv-section rsv-section-step-4">
    <h4 class="text-left">Total number of people expected for this reservation?</h4>
    <br><br>
    <div class="rsvp text-left">
        <div><i class="fa fa-info-circle ml-0 mb-2 mr-1"></i> Pets not allowed</div>
        <div class="single-selector">
            <div role="group" class="input-group mt-1">
                <div class="input-group-prepend">
                    <div class="input-group-text text-nowrap"><i class="fa fa-users"></i></div>
                </div> <input name="input_person" id="choose_person_input" type="number"
                    placeholder="Enter the number of people" min="1" max="60"
                    class="input_person enterNextTab form-control single-selector-input">
            </div>
        </div>
        <div style="display: flex;" class="person_chooser_panel">
            <input type="checkbox" name="whatever" id="choose_person">
            <span>Adjust the number of people by chosen dates</span>
        </div>
        <div class="multiple-selector">

        </div>

    </div>
    <br><br>

    <div class="rsv-next-tab person-config">Next step</div>
    <script>
    personChooser()
    </script>
</div>