<template>
  <header>
    <div class="stickyNav rtl-supported mobileMenu--active">
      <div class="stickyNav__container">
        <a class="gbif-logo" href="/">
          <img src="/images/logo/white.png" height="40" />
        </a>
        <div class="stickyNav__actions">
          <a class="stickyNav__actions__menu gb-icon-menu active-menu"></a>
          <a class="stickyNav__actions__login" href="/login">
            <span class="service-menu__button hoverBox">
              <div class="inline-block">
                <span v-show="!data">{{
                  $t("tank_auth_lang.auth_form_login_button")
                }}</span>
                <span v-show="data">{{ data }}</span>
              </div>
            </span>
          </a>
        </div>
        <div class="mainNavigation">
          <nav class="mainMenu">
            <ul>
              <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data4"
                v-click-outside="offPopUpData4"
              >
                <!-- <a class="mainMenu__parentItem" @click="toggleMenu">{{
                  $t("nbds_lang.menu_conservation_area")
                }}</a> -->
                <a href="/search/protectedarea">
                  Tra cứu khu bảo tồn
                </a>
                <!-- <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li @mouseover="showSubMenu($event, 'khu_bao_ton')">
                          <a href="/search/protectedarea">{{
                            $t("nbds_lang.submenu_conservation_area")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event, 'co_so_bao_ton')">
                          <a href="/search/conservation-infrastructure">{{
                            $t("nbds_lang.submenu_conservation_infrastructure")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event, 'khu_de_xuat')">
                          <a href="/search/conservation-infrastructure">Khu đề xuất</a>
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul> -->
              </li>

              <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data3"
                v-click-outside="offPopUpData3"
              >
                <a class="mainMenu__parentItem" href="/search/data-ecosystem">
                  Tra cứu hệ sinh thái
                </a>
                <!-- <a class="mainMenu__parentItem" @click="toggleMenu">{{
                  $t("nbds_lang.menu_ecosystem")
                }}</a> -->
                <!-- <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li @mouseover="showSubMenu($event, 'he_sinh_thai')">
                          <a href="/search/map-hst">Bản đồ hệ sinh thái</a>
                        </li>
                        <li>
                          <a href="/search/data-ecosystem"
                            >Tra cứu hệ sinh thái</a
                          >
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/ecosystem-service">{{
                            $t("nbds_lang.submenu_ecosystem_services")
                          }}</a>
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul> -->
              </li>

              <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data1"
                v-click-outside="offPopUpData1"
              >
                <!-- <a class="mainMenu__parentItem" @click="toggleMenu">{{
                  $t("nbds_lang.menu_species")
                }}</a> -->
                <a href="/search/species">{{
                  $t("nbds_lang.submenu_search_species")
                }}</a>
                <!-- <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li @mouseover="showSubMenu($event, 'loai')">
                          <a href="/search/species">{{
                            $t("nbds_lang.submenu_search_species")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/occurrence">{{
                            $t("nbds_lang.submenu_appearance")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/dataset">{{
                            $t("nbds_lang.submenu_dataset")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/bieu-do-loai">Biểu đồ đa dạng </a>
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul> -->
              </li>

              <!-- <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data2"
                v-click-outside="offPopUpData2"
              >
                <a class="mainMenu__parentItem" @click="toggleMenu">{{
                  $t("nbds_lang.menu_genetic")
                }}</a>
                <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li
                          @mouseover="showSubMenu($event)"
                          v-for="item in menuData.nhom_gen"
                          :key="item.id"
                        >
                          <a
                            :href="
                              '/search/genetic-resources?nhomGen=' + item.id
                            "
                            >{{ item.name }}</a
                          >
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul>
              </li> -->

              <!-- <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data6"
                v-click-outside="offPopUpData6"
              >
                <a class="mainMenu__parentItem" @click="toggleMenu">{{
                  $t("nbds_lang.submenu_traditional_knowledge")
                }}</a>
                <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li
                          @mouseover="showSubMenu($event)"
                          v-for="item in menuData.nhom_tri_thuc"
                          :key="item.id"
                        >
                          <a
                            :href="'/search/genetic-knowledge?nhom=' + item.id"
                            >{{ item.name }}</a
                          >
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul>
              </li> -->

              <!-- <li class="mainMenu--hasChild mainMenu__firstLevel singleMenu">
                <a href="/search/alien-organisms">{{ "Loài ngoại lai" }}</a>
              </li> -->

              <!-- <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data5"
                v-click-outside="offPopUpData5"
              >
                <a class="mainMenu__parentItem" @click="toggleMenu"
                  >Nỗ lực bảo tồn</a
                >
                <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/legislation-documents">{{
                            $t("nbds_lang.submenu_legislation_documents")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/control-of-alien-organisms">{{
                            $t("nbds_lang.submenu_control_of_alien_organisms")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/international-cooperation">{{
                            $t("nbds_lang.submenu_international_cooperation")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a href="/search/annual-budget">{{
                            $t("nbds_lang.submenu_annual_budget")
                          }}</a>
                        </li>
                        <li @mouseover="showSubMenu($event)">
                          <a
                            href="/search/biodiversity-conservation-initiative"
                            >{{
                              $t(
                                "nbds_lang.submenu_biodiversity_conservation_initiative"
                              )
                            }}</a
                          >
                        </li>
                        <li @mouseover="showSubMenu($event, 'ap_luc')">
                        <li>
                          <a href="/search/apluc">Áp lực lên ĐDSH</a>
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul>
              </li> -->

              <!-- <li
                class="mainMenu--hasChild mainMenu__firstLevel menu-data7"
                v-click-outside="offPopUpData7"
              >
                <a class="mainMenu__parentItem" @click="toggleMenu">Bản đồ</a>
                <ul class="mainMenu__childGroup">
                  <li class="mainMenu__columns">
                    <nav>
                      <ul class="mainMenu__childItem">
                        <li>
                          <a href="/search/map?type=he-sinh-thai"
                            >Hệ sinh thái</a
                          >
                        </li>
                        <li>
                          <a href="/search/map?type=loai-quy-hiem"
                            >Loài quý hiếm</a
                          >
                        </li>
                        <li>
                          <a href="/search/map?type=loai-ngoai-lai"
                            >Loài ngoại lai xâm hại</a
                          >
                        </li>
                        <li>
                          <a href="/search/map?type=dia-gioi"
                            >Địa giới hành chính</a
                          >
                        </li>
                        <li>
                          <a href="/search/map?type=tong-hop"
                            >Bản đồ tổng hợp</a
                          >
                        </li>
                      </ul>
                    </nav>
                  </li>
                </ul>
              </li> -->

              <li class="mainMenu__firstLevel hide-on-laptop">
                <a class="mainMenu__parentItem" href="/login">
                  <span class="ng-scope">User profile</span>
                  <span class="mainMenu__greenText m-l-1 ng-binding"
                    >Login</span
                  >
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <SubMenu
        :top="topSubMenu"
        :left="leftSubMenu"
        :dataList.sync="subMenuData"
        :link="linkSubMenu"
      />
    </div>
  </header>
</template>

<script>
import * as env from "../../env.js";
import vClickOutside from "v-click-outside";
import SubMenu from "./submenu.vue";

export default {
  directives: {
    clickOutside: vClickOutside.directive,
  },
  components: { SubMenu },
  data: function () {
    return {
      data: null,
      menuData: [],
      phanLoais: [
        { name: "Giới", id: "kingdom" },
        { name: "Ngành", id: "phylum" },
        { name: "Lớp", id: "class" },
        { name: "Bộ", id: "order" },
        { name: "Họ", id: "family" },
        { name: "Chi", id: "genus" },
        { name: "Loài", id: "species" },
      ],
      heSinhThais: [
        { name: "Hệ sinh thái rừng kín thường xanh (Độ cao > 600m)" },
        { name: "Hệ sinh thái rừng kín thường xanh (Độ cao < 600m)" },
        { name: "Hệ sinh thái rừng tre nứa xen cây gỗ" },
        { name: "Hệ sinh thái rừng trên núi đá vôi" },
        { name: "Hệ sinh thái trảng cỏ, cây bụi" },
        { name: "Hệ sinh thái đất ngập nước" },
        { name: "Hệ sinh thái dân cư đô thị" },
        { name: "Hệ sinh thái dân cư nông thôn" },
        { name: "Hệ sinh thái rừng trồng" },
        { name: "Hệ sinh thái nông nghiệp" },
      ],
      topSubMenu: "0px",
      leftSubMenu: "0px",
      linkSubMenu: "",
      subMenuData: [],
    };
  },
  mounted() {
    this.checkLogin();
    if (this.$store.state.language) {
      this.$i18n.locale = this.$store.state.language;
    }
    this.getMenuData();
  },
  methods: {
    showSubMenu($e, type) {
      var element = $e.target;
      var divOffset = this.offset(element);
      this.leftSubMenu = +divOffset.left + 197 + "px";
      this.topSubMenu = divOffset.top + "px";
      this.subMenuData =
        type == "loai" ? this.phanLoais : type ? this.menuData[type] : [];
      if (type == "co_so_bao_ton") {
        this.linkSubMenu = "/search/conservation-infrastructure/";
      }
      if (type == "khu_bao_ton") {
        this.linkSubMenu = "/detail/protectedarea/";
      }
      if (type == "loai") {
        this.linkSubMenu = "/search/species?phanloai=";
      }
      if (type == "he_sinh_thai") {
        // this.subMenuData = [...this.heSinhThais];
        this.subMenuData = [];
        this.linkSubMenu = "/search/ecosystem?hst_id";
      }
      // if(type='ap_luc'){
      //   this.linkSubMenu = "/search/apluc";
      // }
    },
    offset(el) {
      var rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      return { top: rect.top + scrollTop, left: rect.left + scrollLeft };
    },
    checkLogin() {
      axios
        .get(env.endpointhttp + "check/login")
        .then((response) => {
          this.data = response.data.name;
        })
        .catch((error) => {})
        .finally();
    },
    async getMenuData() {
      let data = await axios.get(env.endpointhttp + "menudata");
      this.menuData = data.data.data;
    },
    offPopUpData1() {
      if ($(".menu-data1").hasClass("mainMenu--childActive")) {
        $(".menu-data1").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData2() {
      if ($(".menu-data2").hasClass("mainMenu--childActive")) {
        $(".menu-data2").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData3() {
      if ($(".menu-data3").hasClass("mainMenu--childActive")) {
        $(".menu-data3").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData4() {
      if ($(".menu-data4").hasClass("mainMenu--childActive")) {
        $(".menu-data4").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData5() {
      if ($(".menu-data5").hasClass("mainMenu--childActive")) {
        $(".menu-data5").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData6() {
      if ($(".menu-data6").hasClass("mainMenu--childActive")) {
        $(".menu-data6").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpData7() {
      if ($(".menu-data7").hasClass("mainMenu--childActive")) {
        $(".menu-data7").removeClass("mainMenu--childActive");
      }
      this.subMenuData = [];
    },
    offPopUpTool() {
      if ($(".menu-tool").hasClass("mainMenu--childActive")) {
        $(".menu-tool").removeClass("mainMenu--childActive");
      }
    },
    offPopUpLanguage() {
      if ($("#languageMenu").hasClass("is-active")) {
        $("#languageMenu").removeClass("is-active");
      }
      this.subMenuData = [];
    },
    setLanguage(value) {
      this.$store.commit("setLanguage", { amount: value });
    },
    toggleMenu() {
      this.subMenuData = [];
      if ($(".stickyNav").hasClass("mainMenu--firstLevelActive")) {
        $(".stickyNav").removeClass("mainMenu--firstLevelActive");
      } else {
        $(".stickyNav").addClass("mainMenu--firstLevelActive");
      }
    },
  },
};
</script>
<style>
.container {
  display: block;
  position: relative;
  padding: 0px 0px 0px 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 16px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.singleMenu:hover {
  color: yellow;
}

.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkmark {
  position: absolute;
  left: 0;
  top: 0;
  height: 16px;
  width: 16px;
  background-color: #eee;
  border-radius: 50%;
}

.container:hover input ~ .checkmark {
  background-color: #ccc;
}

.container input:checked ~ .checkmark {
  background-color: rgba(97, 168, 97, 0.87);
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.container input:checked ~ .checkmark:after {
  display: block;
}

.container .checkmark:after {
  top: 6px;
  left: 6px;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: white;
}

.mainMenu__parentItem,
.stickyNav__actions__action {
  cursor: pointer;
}

.mainMenu h2 {
  display: block;
}
</style>
