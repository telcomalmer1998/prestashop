<script>
    window.tamaraWidgetConfig = {
        lang: "{$lang}",
        country: "{$country}",
        publicKey: "{$public_key}"
    }
</script>
<script defer type="text/javascript" src="{$url}"></script>
<script src="{$installmentWidgetUrl}"></script>

<script>
window.tamaraAsyncCallback = function () {
    // The init method is optional. You can ignore this one and pass value as attributes (In next step)
    window.TamaraInstallmentPlan.init({
       lang: "{$lang}",
        currency: "{$currency}",
        publicKey: "{$public_key}"
    })

    // This one will call immediately when page loaded. You can move out and call it later when you want
    window.TamaraInstallmentPlan.render()
}
</script>
