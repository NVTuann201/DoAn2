<template>
  <span>
    <a href id="organization"></a>
    <!-- <div class="anchor-block--tabs">
            <h3>{{$t('nbds_lang.organizations.contacts')}}</h3>
    <div class="contactsCard card">-->
    <div class>
      <div href class="inherit contactsCard__name">
        <div>
          <div class="sText mg-bt">{{ organization.name }}</div>
        </div>
      </div>
      <div class="small discreet">
        <div>
          <ul class="inline-bullet-list">
            <li v-if="organization.contacts">
              <span class="mg-bt"
                ><span class="sText">{{
                  $t("nbds_lang.organizations.contacts")
                }}</span>
                {{ `: ${organization.contacts}` }}</span
              >
            </li>
            <li v-if="organization.address">
              <span class="mg-bt"
                ><span class="sText">{{
                  $t("nbds_lang.organizations.address")
                }}</span>
                {{ `: ${organization.address}` }}</span
              >
            </li>
          </ul>
        </div>
        <div v-if="organization.email">
          <ul class="inline-bullet-list">
            <li>
              <a class="sText mg-bt" :href="'mailto:' + organization.email">{{
                organization.email
              }}</a>
            </li>
          </ul>
        </div>
        <div v-if="organization.tel">
          <ul class="inline-bullet-list">
            <li>
              <a
                class="sText mg-bt"
                :href="'unsafe:tel:84' + organization.tel"
                >{{ organization.tel }}</a
              >
            </li>
          </ul>
        </div>
        <div v-if="organization.url">
          <ul class="inline-bullet-list" style="width: 100%">
            <li style="width: 100%">
              <a
                style="
                  text-overflow: ellipsis;
                  white-space: nowrap;
                  overflow: hidden;
                  max-width: 100%;
                  display: block;
                "
                class="breakAll sText mg-bt"
                :href="organization.url"
                >{{ organization.url }}</a
              >
            </li>
          </ul>
        </div>
        <!-- <div v-if="organization.portal_url_element">
          <ul class="inline-bullet-list">
            <li>
              <a
                class="breakAll sText mg-bt"
                :href="organization.portal_url_element"
                >{{ organization.portal_url_element }}</a
              >
            </li>
          </ul>
        </div> -->
      </div>
    </div>
    <!-- </div>
    </div>-->
  </span>
</template>

<script>
export default {
  props: ["value"],
  data: function () {
    return {
      organization: null,
    };
  },
  created() {
    if (this.value) {
      this.organization = this.value;
    }
  },
};
</script>

<style scoped>
.rtl-supported {
  margin: 10px;
  padding: 5px;
}
.contactsCard {
  margin-bottom: 10px;
}
.sText {
  font-size: 1rem;
  font-weight: 700;
}
.mg-bt {
  margin-bottom: 5px;
}
</style>
