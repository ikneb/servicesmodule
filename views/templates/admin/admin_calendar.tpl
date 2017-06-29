
<div id='wrap'>

    <div id='calendar'></div>

    <div style='clear:both'></div>
</div>
<div id="openModal" class="modalDialog">
    <div>
        <a href="#close" title="Close" class="closeModal">X</a>
        <h2>Please enter you name and phone for order service</h2>
        <input type="hidden" name="id_service" value="{$service->id_calendar_services}" >
        <input type="hidden" name="id_product" value="{$id_product}">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="phone" placeholder="Phone">
        <input type="button" class="btn" value="Save">
    </div>
</div>
<div class="b-popup">
    <div class="b-popup-content">
        Saved successfully!
    </div>
</div>