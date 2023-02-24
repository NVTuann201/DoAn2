<template>
  <div>
    <div id="site-wrapper" class="site-wrapper">
      <div feedback></div>
      <div notifications></div>
      <div user-menu></div>
      <div menu-search></div>
      <div language-menu></div>
      <main id="main" class="main" role="main" ui-view>
        <div class="site-content">
          <div class="wrapper-horizontal-stripes home">
            <div class="home__header">
              <div>
                <div class="container--desktop">
                  <div class="home__header__hero-text">
                    <!-- <div class="home__header__name">
                      <a href="http://sotnmt.hanoi.gov.vn/"
                        ><span>SỞ TÀI NGUYÊN & MÔI TRƯỜNG HÀ NỘi</span></a
                      >
                    </div> -->
                    <h1>
                      {{ $t("nbds_lang.system_title") }}
                    </h1>
                    <!-- <div class="home__searchFilters">
                      <a
                        href="/search/protectedarea"
                        class="inherit noUnderline"
                        >{{ $t("nbds_lang.submenu_conservation_area") }}</a
                      >
                      <a href="/search/species" class="inherit noUnderline">{{
                        $t("nbds_lang.submenu_species")
                      }}</a>
                      <a href="/search/dataset" class="inherit noUnderline">{{
                        $t("nbds_lang.submenu_dataset")
                      }}</a>
                      <a href="/search/publisher" class="inherit noUnderline">{{
                        $t("nbds_lang.submenu_publisher")
                      }}</a>
                    </div> -->
                    <!-- <div class="home__header__search-bar search-box">
                      <form>
                        <input
                          type="text"
                          placeholder="Công cụ tìm kiếm nhanh"
                          v-model="keyword"
                          focus-me="true"
                          v-on:keyup.enter="searchAll()"
                        />
                        <a
                          @click="searchAll()"
                          class="
                            search-box__submit
                            gb-icon-search2
                            inherit
                            noUnderline
                          "
                          id="searchall"
                        >
                          <span class="sr-only">Search</span>
                        </a>
                      </form>
                    </div>
                    <div class="home__quicklinks">
                      <a
                        href="https://dadangsinhhocquocgia.ceid.gov.vn/"
                        class="gb-button--brand--transparent"
                        >TRANG LIÊN KẾT: HỆ THỐNG CƠ SỞ DỮ LIỆU ĐA DẠNG SINH HỌC QUỐC GIA</a>
                    </div> -->
                    <div class="img_caption"><a href="" id="author"></a></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="horizontal-stripe light-background p-t-05">
              <div class="container--desktop">
                <!-- <div class="row">
                  <div class="col-xs-12">
                    <div class="card container-fluid">
                      <div class="row summaryStats rtl-bootstrap">
                        <a
                          class="col-xs-12 col-sm-4 col-md-4 summaryStats__item"
                          href="/search/taxon"
                        >
                          <div>
                            <div class="summaryStats__circle">
                              <div
                                class="round-progress-wrapper"
                                color="#71b171"
                                responsive="true"
                                style="
                                  width: 100%;
                                  position: relative;
                                  padding-bottom: 100%;
                                "
                              >
                                <svg
                                  class="round-progress"
                                  xmlns="http://www.w3.org/2000/svg"
                                  role="progressbar"
                                  aria-valuemin="0"
                                  viewBox="0 0 200 200"
                                  aria-valuemax="100"
                                  aria-valuenow="99.9787862139783"
                                  style="
                                    top: 0px;
                                    left: 0px;
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    overflow: hidden;
                                  "
                                >
                                  <circle
                                    fill="none"
                                    cx="100"
                                    cy="100"
                                    r="92.5"
                                    style="
                                      stroke: rgb(219, 239, 239);
                                      stroke-width: 15;
                                    "
                                  ></circle>
                                  <path
                                    fill="none"
                                    transform=""
                                    style="
                                      stroke: rgb(113, 177, 113);
                                      stroke-width: 15;
                                      stroke-linecap: butt;
                                    "
                                    d="M 99.87654524045058 7.50008238424023 A 92.5 92.5 0 1 0 100 7.5"
                                  ></path>
                                </svg>
                              </div>
                            </div>
                            <div class="summaryStats__percentage">
                              <span v-if="data.soloai">{{
                                formatPrice(data.soloai)
                              }}</span>
                              <span v-else>...</span>
                              <div>
                                {{ $t("nbds_lang.table_darwin_core_taxons") }}
                              </div>
                            </div>
                          </div>
                        </a>
                        <a
                          class="col-xs-12 col-sm-4 col-md-4 summaryStats__item"
                          href="/search/dataset"
                        >
                          <div>
                            <div class="summaryStats__circle">
                              <div
                                class="round-progress-wrapper"
                                bgcolor="#dbefef"
                                color="#71b171"
                                responsive="true"
                                style="
                                  width: 100%;
                                  position: relative;
                                  padding-bottom: 100%;
                                "
                              >
                                <svg
                                  class="round-progress"
                                  xmlns="http://www.w3.org/2000/svg"
                                  role="progressbar"
                                  aria-valuemin="0"
                                  viewBox="0 0 200 200"
                                  aria-valuemax="100"
                                  aria-valuenow="100"
                                  style="
                                    top: 0px;
                                    left: 0px;
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    overflow: hidden;
                                  "
                                >
                                  <circle
                                    fill="none"
                                    cx="100"
                                    cy="100"
                                    r="92.5"
                                    style="
                                      stroke: rgb(219, 239, 239);
                                      stroke-width: 15;
                                    "
                                  ></circle>
                                  <path
                                    fill="none"
                                    transform=""
                                    style="
                                      stroke: rgb(113, 177, 113);
                                      stroke-width: 15;
                                      stroke-linecap: butt;
                                    "
                                    d="M 99.99983855704419 7.500000000140886 A 92.5 92.5 0 1 0 100 7.5"
                                  ></path>
                                  <g></g>
                                </svg>
                              </div>
                            </div>
                            <div class="summaryStats__percentage">
                              <span v-if="data.sobodulieu">{{
                                formatPrice(data.sobodulieu)
                              }}</span>
                              <span v-else>...</span>
                              <div>{{ $t("nbds_lang.submenu_dataset") }}</div>
                            </div>
                          </div>
                        </a>
                        <a
                          class="col-xs-12 col-sm-4 col-md-4 summaryStats__item"
                          href="/search/occurrence"
                        >
                          <div>
                            <div class="summaryStats__circle">
                              <div
                                class="round-progress-wrapper"
                                color="#71b171"
                                responsive="true"
                                style="
                                  width: 100%;
                                  position: relative;
                                  padding-bottom: 100%;
                                "
                              >
                                <svg
                                  class="round-progress"
                                  xmlns="http://www.w3.org/2000/svg"
                                  role="progressbar"
                                  aria-valuemin="0"
                                  viewBox="0 0 200 200"
                                  aria-valuemax="100"
                                  aria-valuenow="99.9787862139783"
                                  style="
                                    top: 0px;
                                    left: 0px;
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    overflow: hidden;
                                  "
                                >
                                  <circle
                                    fill="none"
                                    cx="100"
                                    cy="100"
                                    r="92.5"
                                    style="
                                      stroke: rgb(219, 239, 239);
                                      stroke-width: 15;
                                    "
                                  ></circle>
                                  <path
                                    fill="none"
                                    transform=""
                                    style="
                                      stroke: rgb(113, 177, 113);
                                      stroke-width: 15;
                                      stroke-linecap: butt;
                                    "
                                    d="M 99.87654524045058 7.50008238424023 A 92.5 92.5 0 1 0 100 7.5"
                                  ></path>
                                </svg>
                              </div>
                            </div>
                            <div class="summaryStats__percentage">
                              <span v-if="data.solansuathien">{{
                                formatPrice(data.solansuathien)
                              }}</span>
                              <span v-else>...</span>
                              <div>
                                {{ $t("nbds_lang.submenu_occurrence") }}
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                </div> -->

                <article class="wrapper-horizontal-stripes">
                  <section
                    class="horizontal-stripe light-background featuresBlockList"
                  >
                    <div class="container--desktop p-l-0">
                      <div class="featuresBlock featuresBlock-length-3">
                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="https://vuonquocgiabavi.com.vn/"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/vuon-quoc-gia-ba-vi.jpg"
                              alt="Vườn quốc gia Ba Vì là một vườn quốc gia ở Việt Nam, được thành lập năm 1991 theo quyết định số 17-CT ngày 16 tháng 01 năm 1991 của Chủ tịch Hội đồng Bộ trưởng Việt Nam"
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a
                                  href="https://vuonquocgiabavi.com.vn/"
                                  class="inherit"
                                  target="_blank"
                                >
                                  <p>Vườn quốc gia Ba Vì</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/ho-tay.jpg"
                              alt="Hồ Tây trước đây còn có các tên gọi khác như Đầm Xác Cáo, Hồ Kim Ngưu, Lãng Bạc, Dâm Đàm, Đoài Hồ, là một hồ nước tự nhiên lớn nhất ở nội thành thủ đô Hà Nội, nằm ở quận Tây Hồ. Hồ có diện tích hơn 500 ha với chu vi là khoảng 14,8 km"
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Hồ Tây, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/ho-suoi-hai.jpg"
                              alt="Hồ Suối Hai là hồ nước ngọt nhân tạo nằm dưới chân núi Ba Vì, thuộc địa bàn xã Cẩm Lĩnh, Ba Trại, Thụy An, Tản Lĩnh, huyện Ba Vì, thành phố Hà Nội. Công trình được xây dựng vào thập niên 50 của thế kỷ 20"
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Hồ Suối Hai, Ba Vì, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>
                      </div>
                    </div>
                  </section>
                  <section
                    class="horizontal-stripe light-background featuresBlockList"
                  >
                    <div class="container--desktop p-l-0">
                      <div class="featuresBlock featuresBlock-length-3">
                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/ho-dong-mo.jpg"
                              alt="Đồng Mô là một khu du lịch thuộc địa phận thị xã Sơn Tây và một phần huyện Ba Vì, Hà Nội. Khu du lịch Đồng Mô bao gồm một hồ chứa nước rộng khoảng 500 ha, nằm trong vùng chân núi Ba Vì"
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Hồ Đồng Mô, Ba Vì, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/ho-quan-son.jpg"
                              alt="Quan Sơn là tên một hồ nước nằm ở huyện Mỹ Đức Hà Nội, đây là một trong các điểm du lịch của Hà Nội"
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Hồ Quan Sơn, Mỹ Đức, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/rung-lim-co-den-va.jpg"
                              alt="Khu rừng lim cổ thụ gồm 242 cây uớc tính hơn 1000 năm tuổi tạo nên cảnh quan thiên nhiên hùng vĩ."
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Rừng Lim cổ Đền Và, Sơn Tây, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>
                      </div>
                    </div>
                  </section>

                  <section
                    class="horizontal-stripe light-background featuresBlockList"
                  >
                    <div class="container--desktop p-l-0">
                      <div class="featuresBlock featuresBlock-length-3">
                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/chua-thay.jpg"
                              alt="Chùa Thầy là một nhóm những ngôi chùa ở chân núi Sài Sơn, huyện Quốc Oai, tỉnh Hà Tây cũ, nay là xã Sài Sơn, huyện Quốc Oai, Hà Nội, cách trung tâm Hà Nội khoảng 20 km về phía Tây nam, đi theo đường cao tốc Láng - Hòa Lạc. Sài Sơn có tên Nôm là núi Thầy, nên chùa được gọi là chùa Thầy."
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Chùa Thầy, Sài Sơn, Quốc Oai, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/chua-huong.jpg"
                              alt="Chùa Hương là cách nói trong dân gian, trên thực tế chùa Hương hay Hương Sơn là cả một quần thể văn hóa - tôn giáo Việt Nam, gồm hàng chục ngôi chùa thờ Phật, vài ngôi đền thờ thần, các ngôi đình, thờ tín ngưỡng nông nghiệp."
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Chùa Hương, Mỹ Đức, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>

                        <article
                          class="
                            feature
                            card card--spaced card--flexible
                            featureCard
                            hoverBox
                          "
                        >
                          <a
                            href="#"
                            class="card__stripe featureCard__image"
                            target="_blank"
                          >
                            <img
                              src="/images/index/news/khu-di-tich-k9.jpg"
                              alt="Khu Di tích K9 nằm bên bờ sông Đà, thuộc Đá Chông, huyện Ba Vì (Hà Nội), là một trong những địa danh lịch sử, văn hóa - di tích đặc biệt gắn liền với Chủ tịch Hồ Chí Minh kể từ khi Người còn sống đến khi qua đời."
                              class="article-thumb"
                            />
                          </a>
                          <div class="card__stripe--expandable inherit">
                            <div class="card__content">
                              <h4 class="featureCard__title">
                                <a href="#" class="inherit" target="_blank">
                                  <p>Khu di tích K9, Ba Vì, Hà Nội</p>
                                </a>
                              </h4>
                              <div
                                class="featureCard__footer featureCard__comment"
                              ></div>
                            </div>
                          </div>
                        </article>
                      </div>
                    </div>
                  </section>
                </article>
              </div>
            </div>
          </div>
        </div>
        <!-- <Map :quan_huyen="quan_huyen" /> -->
      </main>
    </div>
  </div>
</template>
<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";
import moment from "../../../../node_modules/moment/moment.js";
import homeNew from "./homeNew.vue";
import homeSkeletonNew from "./homeSkeletonNew.vue";
import Map from "../components/conservation-area/map/index";
Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD-MM-YYYY");
  }
});

export default {
  components: {
    Multiselect,
    homeNew,
    homeSkeletonNew,
    Map,
  },
  props: ["quan_huyen"],
  data: function () {
    return {
      data: [],
      keyword: null,
      news: [],
      li: "",
    };
  },
  mounted() {
    axios.get(env.endpointhttp + "home/info").then((response) => {
      this.data = response.data;
    });
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
  },
};
</script>
