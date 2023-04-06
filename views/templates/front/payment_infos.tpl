{extends "$layout"}


{block name="content"}
  <section>
  <p>{l s='You have successfully sent your order'}</p>
    <p>{l s='Your order ID is: '}</p> <span class="bold">{$id_order}</span>
    <p>{l s='Order status:'}</p><span class="bold">{$status}</span>

  </section>
{/block}
