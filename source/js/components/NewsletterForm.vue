<template>
  <div>
    <h2 class="text-center">Zum Abschluss ein E-Mail?</h2>
    <p>Melden Sie sich zu unserem Umfragen-Newsletter an, wir informieren Sie regelmäßig über die Ergebnisse unserer Umfragen.</p>
    <form class="js-cm-form" id="subForm" action="https://www.createsend.com/t/subscribeerror?description=" method="post" data-id="30FEA77E7D0A9B8D7616376B90063231D64469A1EA51DCFD4422C27B24190228EF9BD565173221EF9A19512E227B9DADF9C4E947AC85335607FE668A3062818C">
      <div>
        <div>
          <label class="block text-gray-600 font-semibold">Name </label>
          <input aria-label="Name"
                 id="fieldName" maxlength="200"
                 name="cm-name"
                 placeholder="Name"
                 class="block border border-gray-600 p-2 w-full"
          >
        </div>
        <div>
          <label class="block text-gray-600 font-semibold">E-Mail-Adresse </label>
          <input autocomplete="Email"
                 aria-label="E-Mail-Adresse"
                 class="js-cm-email-input qa-input-email block border border-gray-600 p-2 w-full"
                 id="fieldEmail"
                 maxlength="200"
                 name="cm-yumilj-yumilj"
                 placeholder="E-Mail-Adresse"
                 required=""
                 type="email">
        </div>
        <div>
          <div>
            <input id="egfy" name="cm-ol-egfy" type="checkbox" value="egfy">
            <label for="egfy">Auch zum Standardnewsletter der unabhängigen Immobilien Redaktion anmelden</label>
          </div>
        </div>
        <div>
          <div>
            <div>
              <input aria-required="" id="cm-privacy-consent" name="cm-privacy-consent" required="" type="checkbox">
              <label for="cm-privacy-consent">Ich stimme dem Erhalt von E-Mails der Immobilien Redaktion zu</label>
            </div>
            <input id="cm-privacy-consent-hidden" name="cm-privacy-consent-hidden" type="hidden" value="true">
          </div>
        </div>
      </div>
      <button type="submit" class="bg-primary-100 text-center text-white py-2 w-full mt-4">anmelden</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "NewsletterForm"
}
</script>

<style scoped>

</style>