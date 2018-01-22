<% if $HasKeys %>
  <div $AttributesHTML></div>
<% else %>
  <div class="$AlertClass" role="alert">
    <%t SilverWare\Recaptcha\Fields\RecaptchaField.APIKEYSMISSING 'Recaptcha API keys have not been configured.' %>
  </div>
<% end_if %>
