<h1>Enable virtual product as a service</h1>
<div class="container service-product">
    <diw class="row">
        <input id="service_active" data-toggle="switch" type="checkbox" name="service_active" value="{$service.service_active}">
    </diw>
    <div class="service-product_active">
        <input id="submit_service" type="submit" class="btn btn-primary save uppercase" value="Save service options" data-toggle="tooltip" title="" data-original-title="Save the product and stay on the current page: CTRL+S">

        <div class="row time-work-wrapp">
            <h3>Time work service</h3>
            <input type="hidden" name="id_service" id="id_service" value="{$service.id_calendar_services}">
            <input type="hidden" name="id_product" id="id_product" value="{$id_product}">
            <div class="col-md-3">
                <label class="form-control-label">{l s='Start service time'  mod=servicesproduct}</label>
                <select id="start" name="start" class="form-control select2-hidden-accessible"
                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                    {$options_select_start}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-control-label">{l s='End service time'  mod=servicesproduct}</label>
                <select id="end" name="end" class="form-control select2-hidden-accessible"
                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                    {$options_select_end}
                </select>
            </div>
        </div>
        <div class="row step-wrapp">
            <input type="hidden" value="1" name="type-step"/>
            <h3>Step time</h3>
            {*<div class="col-md-3 " >
                <label class="form-control-label">{l s='Select step'  mod=servicesproduct}</label>
                <select id="type-step" name="type-step" class="form-control select2-hidden-accessible"
                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                    <option value="0" >Select step</option>
                    <option value="1" selected>Time</option>
                    <option value="2" {if $service.type_step == 2}selected{/if}>Days</option>
                </select>
            </div>*}
            <div class="col-md-3 step-hours">
                <label class="form-control-label">{l s='Hours'  mod=servicesproduct}</label>
                <select id="step_time" name="step_time" class="form-control select2-hidden-accessible"
                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                    {$step_time}
                </select>
            </div>
            {*<div class="col-md-3 step-days">
                <label class="form-control-label">{l s='Days'  mod=servicesproduct}</label>
                <select id="step_days" name="step_days" class="form-control select2-hidden-accessible"
                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                    <option value="0">Select count days</option>
                    {for $foo=1 to 7 }
                        <option value="{$foo}" {if $selected.step_days == $foo} selected {/if}>{$foo}</option>
                    {/for}
                </select>
            </div>*}
        </div>
        <div class="row weekends-wrapp">
            <h3>Working days</h3>
            <div class="col-md-3">
                <div class="weekday-select" data-name="order_day" id="days">
                    <div class="week-parts">
                        <label>
                            <input type="checkbox" data-values="0,1,2,3,4,5,6"> {l s='Any day'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" data-values="0,6"> {l s='Weekends'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" data-values="1,2,3,4,5"> {l s='Weekdays'  mod=autorestocking}
                        </label>
                    </div>
                    <div class="days">
                        <label>
                            <input type="checkbox" value="1" name="order_day"  {if  preg_match('/1/',$working_days)}checked{/if}> {l s='Monday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="2" name="order_day"  {if  preg_match('/2/',$working_days)}checked{/if}> {l s='Tuesday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="3" name="order_day"  {if preg_match('/3/',$working_days)}checked{/if}> {l s='Wednesday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="4"  name="order_day"  {if  preg_match('/4/',$working_days)}checked{/if}> {l s='Thursday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="5"  name="order_day"  {if  preg_match('/5/',$working_days)}checked{/if}> {l s='Friday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="6"  name="order_day"  {if  preg_match('/6/',$working_days)}checked{/if}> {l s='Saturday'  mod=autorestocking}
                        </label>
                        <label>
                            <input type="checkbox" value="0"  name="order_day"  {if preg_match('/0/',$working_days)}checked{/if}> {l s='Sunday'  mod=autorestocking}
                        </label>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
<script>
    var widget = new WeekdayWidget(document.getElementById('days'));
</script>
