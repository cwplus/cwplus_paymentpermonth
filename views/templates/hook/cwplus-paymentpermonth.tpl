<div class="form-group col-md-12">
    <h2>{l s="Payment per month"}
        <span class="help-box" data-toggle="popover" data-content="{l s='It\'s using for payment per month'}" ></span>
    </h2>
</div>
<div class="col-xl-2 col-lg-3">
    <label class="form-control-label" for="form-paymentpermonth-advance">{l s="Advance"} :</label>
    <div class="input-group advance">
        <input id="form-paymentpermonth-advance" class="form-control" name="paymentpermonth[advance]" value="{$paymentpermonth.advance}" type="text">
        <span class="input-group-addon"> {$sign}</span>
        </div>
    </div>
<div class="col-xl-2 col-lg-3">
    <label class="form-control-label" for="form-paymentpermonth-per-month">{l s="Price per month"} :</label>
    <div class="input-group advance">
        <input id="form-paymentpermonth-per-month" class="form-control" name="paymentpermonth[per_month]" value="{$paymentpermonth.per_month}" type="text">
        <span class="input-group-addon"> {$sign}</span>
        </div>
    </div>
<div class="col-xl-2 col-lg-3">
    <label class="form-control-label" for="form-paymentpermonth-nbr-month">{l s="Number of months"} :</label>
    <div class="input-group advance">
        <input id="form-paymentpermonth-nbr-month" class="form-control" name="paymentpermonth[nbr_month]" value="{$paymentpermonth.nbr_month}" type="text">
    </div>
</div>